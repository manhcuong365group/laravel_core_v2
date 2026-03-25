<?php

namespace App\Data;

use Illuminate\Http\Request;

class OrderData
{
    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function __construct(
        public string $customer_name,
        public string $customer_phone,
        public ?string $customer_email,
        public ?string $customer_address,
        public float $shipping_fee,
        public string $status,
        public ?string $notes,
        public ?string $admin_notes,
        public array $items,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            customer_name: $data['customer_name'],
            customer_phone: $data['customer_phone'],
            customer_email: $data['customer_email'] ?? null,
            customer_address: $data['customer_address'] ?? null,
            shipping_fee: (float) ($data['shipping_fee'] ?? 0),
            status: $data['status'],
            notes: $data['notes'] ?? null,
            admin_notes: $data['admin_notes'] ?? null,
            items: $data['items'] ?? [],
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->all());
    }

    public function toArray(): array
    {
        return [
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'customer_address' => $this->customer_address,
            'shipping_fee' => $this->shipping_fee,
            'status' => $this->status,
            'notes' => $this->notes,
            'admin_notes' => $this->admin_notes,
        ];
    }
}
