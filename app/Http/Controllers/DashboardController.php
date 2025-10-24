<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Interaccion;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $total_clientes = Cliente::count();
        $clientes_mes_anterior = Cliente::whereMonth('created_at', now()->subMonth()->month)
                                        ->whereYear('created_at', now()->subMonth()->year)
                                        ->count();
        $incremento_clientes = $clientes_mes_anterior > 0 
            ? round((($total_clientes - $clientes_mes_anterior) / $clientes_mes_anterior) * 100) 
            : 0;

        $total_interacciones = Interaccion::count();
        $interacciones_ayer = Interaccion::whereDate('created_at', now()->subDay())->count();
        $incremento_interacciones = $total_interacciones - $interacciones_ayer;

        $recordatorios_urgentes = Interaccion::where('estado', 'pendiente')
                                            ->where('fecha_seguimiento', '<=', now()->addDays(3))
                                            ->count();

        $ventas_mes = Venta::whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)
                          ->sum('total');
        
        $ventas_mes_anterior = Venta::whereMonth('created_at', now()->subMonth()->month)
                                   ->whereYear('created_at', now()->subMonth()->year)
                                   ->sum('total');
        
        $incremento_ventas = $ventas_mes_anterior > 0 
            ? round((($ventas_mes - $ventas_mes_anterior) / $ventas_mes_anterior) * 100) 
            : 0;

        // Ventas mensuales del año actual
        $ventas_mensuales = [];
        for ($i = 1; $i <= date('n'); $i++) {
            $monto = Venta::whereMonth('created_at', $i)
                         ->whereYear('created_at', date('Y'))
                         ->sum('total');
            
            $ventas_mensuales[] = [
                'mes' => date('M', mktime(0, 0, 0, $i, 1)),
                'monto' => $monto
            ];
        }

        // Distribución de clientes por tipo de vehículo
        $distribucion_clientes = [
            [
                'categoria' => 'Motos',
                'porcentaje' => $total_clientes > 0 ? round((Cliente::where('tipo_vehiculo', 'moto')->count() / $total_clientes) * 100) : 0
            ],
            [
                'categoria' => 'Carros',
                'porcentaje' => $total_clientes > 0 ? round((Cliente::where('tipo_vehiculo', 'carro')->count() / $total_clientes) * 100) : 0
            ],
            [
                'categoria' => 'Bicicletas',
                'porcentaje' => $total_clientes > 0 ? round((Cliente::where('tipo_vehiculo', 'bicicleta')->count() / $total_clientes) * 100) : 0
            ],
        ];

        $data = [
            'total_clientes' => $total_clientes,
            'incremento_clientes' => $incremento_clientes,
            'total_interacciones' => $total_interacciones,
            'incremento_interacciones' => $incremento_interacciones,
            'recordatorios_urgentes' => $recordatorios_urgentes,
            'ventas_mes' => $ventas_mes,
            'incremento_ventas' => $incremento_ventas,
            'ventas_mensuales' => $ventas_mensuales,
            'distribucion_clientes' => $distribucion_clientes,
        ];
        
        return view('dashboard.index', $data);
    }
}