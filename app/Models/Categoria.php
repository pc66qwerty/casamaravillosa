<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'estado'
    ];

    // Relaciones
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    // Scope para categorías activas
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activo');
    }
}