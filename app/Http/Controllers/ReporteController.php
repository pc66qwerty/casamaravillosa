<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $fecha_inicio = $request->fecha_inicio ?? now()->startOfMonth()->format('Y-m-d');
        $fecha_fin = $request->fecha_fin ?? now()->format('Y-m-d');

        // Resumen de ventas
        $ventas_resumen = Venta::whereBetween('created_at', [$fecha_inicio, $fecha_fin])
                              ->selectRaw('
                                  COUNT(*) as total_ventas,
                                  SUM(total) as ingresos_totales,
                                  SUM(descuento) as descuentos_totales,
                                  AVG(total) as ticket_promedio
                              ')
                              ->first();

        // Ventas por día
        $ventas_diarias = Venta::whereBetween('created_at', [$fecha_inicio, $fecha_fin])
                              ->selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad, SUM(total) as total')
                              ->groupBy('fecha')
                              ->orderBy('fecha')
                              ->get();

        // Productos más vendidos
        $productos_mas_vendidos = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->whereBetween('ventas.created_at', [$fecha_inicio, $fecha_fin])
            ->select(
                'productos.nombre',
                'productos.codigo',
                DB::raw('SUM(detalle_ventas.cantidad) as cantidad_vendida'),
                DB::raw('SUM(detalle_ventas.subtotal) as ingresos')
            )
            ->groupBy('productos.id', 'productos.nombre', 'productos.codigo')
            ->orderByDesc('cantidad_vendida')
            ->limit(10)
            ->get();

        // Clientes top
        $clientes_top = Cliente::withCount(['ventas' => function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);
            }])
            ->withSum(['ventas' => function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);
            }], 'total')
            ->having('ventas_count', '>', 0)
            ->orderByDesc('ventas_sum_total')
            ->limit(10)
            ->get();

        // Ventas por método de pago
        $ventas_por_metodo = Venta::whereBetween('created_at', [$fecha_inicio, $fecha_fin])
            ->select('metodo_pago', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->groupBy('metodo_pago')
            ->get();

        // Productos con stock bajo
        $productos_stock_bajo = Producto::whereHas('inventarios', function($q) {
            $q->whereRaw('cantidad_actual <= productos.stock_minimo');
        })->with(['categoria', 'inventarios' => function($q) {
            $q->latest()->first();
        }])->limit(10)->get();

        // Movimientos de inventario recientes
        $movimientos_inventario = Inventario::with(['producto', 'usuario'])
            ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('reportes.index', compact(
            'fecha_inicio',
            'fecha_fin',
            'ventas_resumen',
            'ventas_diarias',
            'productos_mas_vendidos',
            'clientes_top',
            'ventas_por_metodo',
            'productos_stock_bajo',
            'movimientos_inventario'
        ));
    }

    public function exportar(Request $request)
    {
        $fecha_inicio = $request->fecha_inicio ?? now()->startOfMonth()->format('Y-m-d');
        $fecha_fin = $request->fecha_fin ?? now()->format('Y-m-d');

        // Obtener todos los datos del reporte
        $ventas_resumen = Venta::whereBetween('created_at', [$fecha_inicio, $fecha_fin])
                              ->selectRaw('
                                  COUNT(*) as total_ventas,
                                  SUM(total) as ingresos_totales,
                                  SUM(descuento) as descuentos_totales,
                                  AVG(total) as ticket_promedio
                              ')
                              ->first();

        $ventas = Venta::with(['cliente', 'usuario', 'detalles.producto'])
                      ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
                      ->orderBy('created_at', 'desc')
                      ->get();

        $productos_mas_vendidos = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->whereBetween('ventas.created_at', [$fecha_inicio, $fecha_fin])
            ->select(
                'productos.nombre',
                'productos.codigo',
                DB::raw('SUM(detalle_ventas.cantidad) as cantidad_vendida'),
                DB::raw('SUM(detalle_ventas.subtotal) as ingresos')
            )
            ->groupBy('productos.id', 'productos.nombre', 'productos.codigo')
            ->orderByDesc('cantidad_vendida')
            ->limit(20)
            ->get();

        $filename = "reporte_ventas_" . date('Y-m-d_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function() use ($ventas_resumen, $ventas, $productos_mas_vendidos, $fecha_inicio, $fecha_fin) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
            
            // Sección 1: Resumen General
            fputcsv($file, ['REPORTE DE VENTAS']);
            fputcsv($file, ['Período:', date('d/m/Y', strtotime($fecha_inicio)), 'al', date('d/m/Y', strtotime($fecha_fin))]);
            fputcsv($file, []);
            fputcsv($file, ['RESUMEN GENERAL']);
            fputcsv($file, ['Total Ventas:', $ventas_resumen->total_ventas]);
            fputcsv($file, ['Ingresos Totales:', 'Q' . number_format($ventas_resumen->ingresos_totales, 2)]);
            fputcsv($file, ['Descuentos Totales:', 'Q' . number_format($ventas_resumen->descuentos_totales, 2)]);
            fputcsv($file, ['Ticket Promedio:', 'Q' . number_format($ventas_resumen->ticket_promedio, 2)]);
            fputcsv($file, []);
            fputcsv($file, []);
            
            // Sección 2: Detalle de Ventas
            fputcsv($file, ['DETALLE DE VENTAS']);
            fputcsv($file, [
                'N° Venta',
                'Fecha',
                'Cliente',
                'Vendedor',
                'Subtotal',
                'Descuento',
                'Total',
                'Método Pago',
                'Estado'
            ]);
            
            foreach ($ventas as $venta) {
                fputcsv($file, [
                    $venta->numero_venta,
                    $venta->created_at->format('d/m/Y H:i'),
                    $venta->cliente->nombre_completo ?? 'N/A',
                    $venta->usuario->name ?? 'N/A',
                    'Q' . number_format($venta->subtotal, 2),
                    'Q' . number_format($venta->descuento, 2),
                    'Q' . number_format($venta->total, 2),
                    ucfirst($venta->metodo_pago),
                    ucfirst($venta->estado)
                ]);
            }
            
            fputcsv($file, []);
            fputcsv($file, []);
            
            // Sección 3: Productos Más Vendidos
            fputcsv($file, ['PRODUCTOS MÁS VENDIDOS']);
            fputcsv($file, ['Código', 'Producto', 'Cantidad Vendida', 'Ingresos']);
            
            foreach ($productos_mas_vendidos as $producto) {
                fputcsv($file, [
                    $producto->codigo,
                    $producto->nombre,
                    $producto->cantidad_vendida,
                    'Q' . number_format($producto->ingresos, 2)
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // MÉTODO DE EXPORTACIÓN ADICIONAL
    public function export(Request $request)
    {
        // Redirigir al método exportar existente
        return $this->exportar($request);
    }
}