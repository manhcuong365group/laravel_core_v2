<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'original_url',
        'short_url',
        'description',
        'click_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'click_count' => 'integer',
    ];

    public function getShortUrlFullAttribute(): string
    {
        return $this->short_url ? url('/l/' . $this->short_url) : '';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
