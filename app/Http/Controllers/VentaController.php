<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with(['cliente', 'usuario', 'detalles']);

        // Filtros
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('numero_venta', 'like', "%{$buscar}%")
                  ->orWhereHas('cliente', function($q2) use ($buscar) {
                      $q2->where('nombre', 'like', "%{$buscar}%")
                         ->orWhere('apellido', 'like', "%{$buscar}%");
                  });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $ventas = $query->orderBy('created_at', 'desc')->paginate(15);

        // Estadísticas
        $stats = [
            'total_ventas' => Venta::count(),
            'ventas_hoy' => Venta::whereDate('created_at', today())->count(),
            'total_mes' => Venta::whereMonth('created_at', now()->month)->sum('total'),
            'pendientes' => Venta::where('estado', 'pendiente')->count(),
        ];

        return view('ventas.index', compact('ventas', 'stats'));
    }

    public function create()
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        $productos = Producto::activos()->with(['categoria', 'inventarios' => function($q) {
            $q->latest()->first();
        }])->orderBy('nombre')->get();

        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,credito',
            'descuento' => 'nullable|numeric|min:0',
            'notas' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Calcular totales
            $subtotal = 0;
            foreach ($validated['productos'] as $prod) {
                $subtotal += $prod['cantidad'] * $prod['precio'];
            }

            $descuento = $validated['descuento'] ?? 0;
            $total = $subtotal - $descuento;

            // Crear venta
            $venta = Venta::create([
                'cliente_id' => $validated['cliente_id'],
                'usuario_id' => Auth::id() ?? 1,
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'total' => $total,
                'metodo_pago' => $validated['metodo_pago'],
                'notas' => $validated['notas'],
                'estado' => 'completada',
            ]);

            // Crear detalles y actualizar inventario
            foreach ($validated['productos'] as $prod) {
                $producto = Producto::findOrFail($prod['id']);
                
                // Verificar stock
                if ($producto->stock_actual < $prod['cantidad']) {
                    throw new \Exception("Stock insuficiente para: {$producto->nombre}");
                }

                // Crear detalle
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $prod['id'],
                    'cantidad' => $prod['cantidad'],
                    'precio_unitario' => $prod['precio'],
                ]);

                // Actualizar inventario
                $stockActual = $producto->stock_actual;
                $nuevoStock = $stockActual - $prod['cantidad'];

                Inventario::create([
                    'producto_id' => $prod['id'],
                    'cantidad_actual' => $nuevoStock,
                    'cantidad_entrada' => 0,
                    'cantidad_salida' => $prod['cantidad'],
                    'tipo_movimiento' => 'salida',
                    'motivo' => "Venta #{$venta->numero_venta}",
                    'usuario_id' => Auth::id() ?? 1,
                ]);
            }

            DB::commit();

            return redirect()->route('ventas.show', $venta)
                ->with('success', 'Venta registrada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar la venta: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $venta = Venta::with(['cliente', 'usuario', 'detalles.producto.categoria'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    // MÉTODO DE EXPORTACIÓN
    public function export(Request $request)
    {
        $query = Venta::with(['cliente', 'usuario', 'detalles']);

        // Aplicar los mismos filtros del index
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('numero_venta', 'like', "%{$buscar}%")
                  ->orWhereHas('cliente', function($q2) use ($buscar) {
                      $q2->where('nombre', 'like', "%{$buscar}%")
                         ->orWhere('apellido', 'like', "%{$buscar}%");
                  });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $ventas = $query->orderBy('created_at', 'desc')->get();
        
        $filename = "ventas_" . date('Y-m-d_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function() use ($ventas) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para UTF-8
            
            // Encabezados
            fputcsv($file, [
                'N° Venta',
                'Cliente',
                'Vendedor',
                'Subtotal',
                'Descuento',
                'Total',
                'Método Pago',
                'Estado',
                'Cantidad Productos',
                'Notas',
                'Fecha'
            ]);
            
            // Datos
            foreach ($ventas as $venta) {
                fputcsv($file, [
                    $venta->numero_venta,
                    $venta->cliente->nombre_completo ?? 'N/A',
                    $venta->usuario->name ?? 'N/A',
                    'Q' . number_format($venta->subtotal, 2),
                    'Q' . number_format($venta->descuento, 2),
                    'Q' . number_format($venta->total, 2),
                    ucfirst($venta->metodo_pago),
                    ucfirst($venta->estado),
                    $venta->detalles->count(),
                    $venta->notas ?? '',
                    $venta->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}