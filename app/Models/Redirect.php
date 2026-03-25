<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'old_url',
        'new_url',
        'status_code',
        'hit_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function incrementHitCount(): void
    {
        $this->increment('hit_count');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function findByOldUrl(string $url): ?self
    {
        return static::active()->where('old_url', $url)->first();
    }
}
