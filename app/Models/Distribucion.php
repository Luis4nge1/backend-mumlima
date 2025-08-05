<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distribucion extends Model
{
    use HasFactory;

    protected $table = 'distribuciones';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the usuarios for the distribucion.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(UsuarioFiscalizacion::class, 'distribucion_id');
    }
}