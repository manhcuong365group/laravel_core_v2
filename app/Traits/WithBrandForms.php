<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait WithBrandForms
{
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $website = '';
    public string $order = '0';
    public bool $is_active = true;
    public $logo;

    /**
     * Common rules for Brands.
     */
    protected function brandRules(int $ignoreId = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug' . ($ignoreId ? ",$ignoreId" : ''),
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Auto-generate slug when name is updated.
     */
    public function updatedName(): void
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }
}
