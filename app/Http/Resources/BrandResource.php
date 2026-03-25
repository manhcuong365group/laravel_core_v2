<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'website' => $this->website,
            'is_active' => $this->is_active,
            'order' => $this->order,
            'logo' => $this->getFirstMediaUrl('logo') ?: null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
