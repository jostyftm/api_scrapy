<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'icon'
    ];

    /**
     * 
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'resource_type_id');
    }
}
