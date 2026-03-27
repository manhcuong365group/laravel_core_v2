<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasSlug, InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'sku', 'price', 'sale_price', 'stock_status', 'stock_quantity', 'is_active', 'is_featured', 'category_id', 'brand_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Sản phẩm đã được {$eventName}");
    }

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'short_description',
        'content',
        'price',
        'sale_price',
        'stock_quantity',
        'stock_status',
        'category_id',
        'brand_id',
        'is_active',
        'is_featured',
        'order',
        'view_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'featured_image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values')
            ->withPivot('price_adjustment');
    }

    public function tags(): BelongsToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile();

        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(600)
            ->height(600)
            ->nonQueued();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format((float) $this->price, 0, ',', '.') . '₫';
    }

    public function getFormattedSalePriceAttribute(): ?string
    {
        return $this->sale_price
            ? number_format((float) $this->sale_price, 0, ',', '.') . '₫'
            : null;
    }

    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }
}
