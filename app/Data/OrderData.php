<?php

namespace App\Data;

use Illuminate\Http\Request;

class OrderData extends BaseData
{
    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function __construct(
        public string $customer_name,
        public string $customer_phone,
        public ?string $customer_email = null,
        public ?string $customer_address = null,
        public float $shipping_fee = 0,
        public string $status = 'pending',
        public ?string $notes = null,
        public ?string $admin_notes = null,
        public array $items = [],
    ) {
    }

    /**
     * Override toArray() to remove items as they are stored in order_items table.
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        unset($data['items']);
        return $data;
    }
}
