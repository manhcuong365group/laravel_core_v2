<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasSlug, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'template',
        'is_active',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'featured_image',
        'layout_blocks',
        'use_layout_builder',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'use_layout_builder' => 'boolean',
        'layout_blocks' => 'array',
    ];

    /**
     * Get the layout blocks as a collection.
     */
    public function getBlocksAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->layout_blocks ?? []);
    }

    /**
     * Check if this page uses the layout builder.
     */
    public function usesLayoutBuilder(): bool
    {
        return $this->use_layout_builder && !empty($this->layout_blocks);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile();

        $this->addMediaCollection('content_images');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
