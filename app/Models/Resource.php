<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'description',
        'state',
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

    /**
     * Scope search
     * 
     * @param String $s
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrWhereTitle(Builder $query, $words = array())
    {
        if(is_array($words) && !empty($words)){
            foreach($words as $word){
                $query->orWhere('title', 'LIKE', "%{$word}%");
            }
            
            return $query;
        }
    }

    /**
     * Scope search
     * 
     * @param String $s
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrWhereDescription(Builder $query, $words = array())
    {
        if(is_array($words) && !empty($words)){
            foreach($words as $word){
                $query->orWhere('description', 'LIKE', "%{$word}%");
            }
            
            return $query;
        }
    }
}
