<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Search extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'website_id',
    ];

    /**
     * 
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    /**
     * 
     */
    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class, 'search_keywords', 'search_id', 'keyword_id');
    }

}
