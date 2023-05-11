<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url'
    ];

    /**
     * 
     */
    public function searches(): HasMany
    {
        return $this->hasMany(Search::class, 'website_id');
    }

    /**
     * 
     */
    public function webConfiguration(): HasOne
    {
        return $this->hasOne(WebsiteConfiguration::class, 'website_id');
    }
}
