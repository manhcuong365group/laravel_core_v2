<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $table = 'backend_menus';

    protected $fillable = [
        'parent_id',
        'label',
        'icon',
        'route_name',
        'route_params',
        'permission',
        'order_column',
        'group_label',
        'is_active',
    ];

    protected $casts = [
        'route_params' => 'array',
        'is_active' => 'boolean',
    ];

    // Quan hệ menu cha - con
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order_column');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Scope sắp xếp
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_column');
    }

    // Scope chỉ lấy menu gốc (không có cha)
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id')->orderBy('order_column');
    }
}

