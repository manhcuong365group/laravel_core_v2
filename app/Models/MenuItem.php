<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'target',
        'icon',
        'css_class',
        'linkable_type',
        'linkable_id',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getChildren(): array
    {
        return $this->children->map(function ($child) {
            return $child->toArray() + ['children' => $child->getChildren()];
        })->toArray();
    }

    public function getUrlAttribute($value): string
    {
        if ($this->linkable) {
            return $this->linkable->url ?? url($this->linkable->slug ?? '/');
        }

        return $value ?? '#';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
