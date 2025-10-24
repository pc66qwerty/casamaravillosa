<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria_id',
        'marca',
        'modelo',
        'precio_compra',
        'precio_venta',
        'stock_minimo',
        'ubicacion',
        'imagen',
        'estado'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
    ];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    // Accessor para stock actual
    public function getStockActualAttribute()
    {
        return $this->inventarios()->latest()->first()->cantidad_actual ?? 0;
    }

    // Accessor para margen de ganancia
    public function getMargenGananciaAttribute()
    {
        if ($this->precio_compra > 0) {
            return (($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100;
        }
        return 0;
    }

    // Scope para productos activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    // Scope para productos con stock bajo
    public function scopeStockBajo($query)
    {
        return $query->whereHas('inventarios', function($q) {
            $q->whereRaw('cantidad_actual <= stock_minimo');
        });
    }
}