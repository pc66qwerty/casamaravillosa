<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaccion extends Model
{
    use HasFactory;

    protected $table = 'interacciones';

    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'tipo',
        'descripcion',
        'fecha_seguimiento',
        'estado'
    ];

    protected $casts = [
        'fecha_seguimiento' => 'date',
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

    // Scope para interacciones pendientes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    // Scope para seguimientos de hoy
    public function scopeSeguimientoHoy($query)
    {
        return $query->where('fecha_seguimiento', date('Y-m-d'));
    }
}