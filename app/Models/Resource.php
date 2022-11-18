<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'resource_type_id',
        'search_id'
    ];

    /**
     * 
     */
    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id');
    }

    /**
     * 
     */
    public function search(): BelongsTo
    {
        return $this->belongsTo(Search::class, 'search_id');
    }
}
