<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'type_id',
        'name',
        'short_name',
        'description',
        'contact_email',
        'contact_phone',
        'address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parent entity.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'parent_id');
    }

    /**
     * Get the child entities.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Entity::class, 'parent_id');
    }

    /**
     * Get the entity type.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(EntityType::class, 'type_id');
    }

    /**
     * Get all descendants recursively.
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ancestors recursively.
     */
    public function ancestors()
    {
        $ancestors = collect();
        $parent = $this->parent;
        
        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }
        
        return $ancestors;
    }

    /**
     * Get the usuarios fiscalizacion for the entity.
     */
    public function usuariosFiscalizacion(): HasMany
    {
        return $this->hasMany(UsuarioFiscalizacion::class, 'entity_id');
    }
}