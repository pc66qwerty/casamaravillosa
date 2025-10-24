<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'dpi',
        'direccion',
        'tipo_vehiculo',
        'marca_vehiculo',
        'modelo_vehiculo',
        'placa_vehiculo',
        'estado',
        'notas'
    ];

    // Relaciones
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function interacciones()
    {
        return $this->hasMany(Interaccion::class);
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    // Scope para clientes activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}