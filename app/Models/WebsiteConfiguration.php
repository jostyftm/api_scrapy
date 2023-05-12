<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'state',
        'website_id',
        'query_search_variable',
        'query_separator',
        'tag_resource_link',
        'tag_resource_title',
        'tag_resource_description',
        'tag_resource_next_page',
        'tag_resource_list_posts'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'state' => 'boolean',
    ];


    /**
     * 
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
