<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'label',
        'description',
        'is_public',
        'order',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'number' => (float) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    /**
     * Set setting value by key
     */
    public static function set(string $key, $value, array $attributes = []): static
    {
        $valueToStore = is_array($value) ? json_encode($value) : $value;

        return static::updateOrCreate(
            ['key' => $key],
            array_merge(['value' => $valueToStore], $attributes)
        );
    }

    /**
     * Get all settings by group
     */
    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
            ->orderBy('order')
            ->get()
            ->mapWithKeys(fn ($setting) => [$setting->key => static::get($setting->key)])
            ->toArray();
    }

    /**
     * Get all public settings (for frontend)
     */
    public static function getPublic(): array
    {
        return static::where('is_public', true)
            ->get()
            ->mapWithKeys(fn ($setting) => [$setting->key => static::get($setting->key)])
            ->toArray();
    }
}
