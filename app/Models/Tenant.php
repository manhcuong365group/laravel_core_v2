<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'name',
        'theme',
        'modules',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'modules' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
    ];
}
