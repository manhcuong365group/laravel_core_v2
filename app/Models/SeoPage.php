<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_type',
        'title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_card',
        'schema_markup',
        'custom_head',
        'custom_body',
    ];

    protected $casts = [
        'schema_markup' => 'array',
    ];

    public static function getForPage(string $pageType): ?self
    {
        return static::where('page_type', $pageType)->first();
    }
}
