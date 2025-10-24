<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relaciones
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Calcular subtotal automáticamente
    public static function boot()
    {
        parent::boot();

        static::creating(function ($detalle) {
            $detalle->subtotal = $detalle->cantidad * $detalle->precio_unitario;
        });

        static::updating(function ($detalle) {
            $detalle->subtotal = $detalle->cantidad * $detalle->precio_unitario;
        });
    }
}