<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'stock' => 'integer',
            'estado' => 'boolean',
        ];
    }

    /**
     * Relación: Un producto pertenece a una categoría.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Relación: Un producto tiene muchos detalles de pedidos.
     */
    public function pedidoDetalles(): HasMany
    {
        return $this->hasMany(PedidoDetalle::class, 'producto_id');
    }

    /**
     * Scope para productos activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    /**
     * Scope para productos con stock disponible.
     */
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
