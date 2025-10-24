<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_venta',
        'cliente_id',
        'usuario_id',
        'subtotal',
        'descuento',
        'total',
        'estado',
        'metodo_pago',
        'notas'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    // Generar número de venta automático
    public static function boot()
    {
        parent::boot();

        static::creating(function ($venta) {
            if (empty($venta->numero_venta)) {
                $ultimaVenta = static::latest('id')->first();
                $numero = $ultimaVenta ? $ultimaVenta->id + 1 : 1;
                $venta->numero_venta = 'VEN-' . date('Y') . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    // Scope para ventas del mes actual
    public function scopeDelMesActual($query)
    {
        return $query->whereMonth('created_at', date('m'))
                     ->whereYear('created_at', date('Y'));
    }
}