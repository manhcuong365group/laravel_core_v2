<?php

namespace App\Data;

use Illuminate\Http\Request;

class NewsletterData
{
    public function __construct(
        public string $email,
        public ?string $name = null,
        public string $status = 'subscribed',
        public ?string $source = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            name: $data['name'] ?? null,
            status: $data['status'] ?? 'subscribed',
            source: $data['source'] ?? null,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->input('email'),
            name: $request->input('name'),
            status: $request->input('status', 'subscribed'),
            source: $request->input('source'),
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'status' => $this->status,
            'source' => $this->source,
        ];
    }
}
