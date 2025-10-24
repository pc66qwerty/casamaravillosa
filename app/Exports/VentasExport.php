<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VentasExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fecha_inicio;
    protected $fecha_fin;

    public function __construct($fecha_inicio, $fecha_fin)
    {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
    }

    public function collection()
    {
        return Venta::with(['cliente', 'usuario'])
            ->whereBetween('created_at', [$this->fecha_inicio, $this->fecha_fin])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Número Venta',
            'Cliente',
            'Vendedor',
            'Fecha',
            'Subtotal',
            'Descuento',
            'Total',
            'Método Pago',
            'Estado'
        ];
    }

    public function map($venta): array
    {
        return [
            $venta->numero_venta,
            $venta->cliente->nombre_completo,
            $venta->usuario->name,
            $venta->created_at->format('d/m/Y H:i'),
            $venta->subtotal,
            $venta->descuento,
            $venta->total,
            ucfirst($venta->metodo_pago),
            ucfirst($venta->estado)
        ];
    }
}