<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'word'
    ];

    /**
     * 
     */
    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Search::class, 'search_keywords', 'keyword_id', 'search_id');
    }
}
