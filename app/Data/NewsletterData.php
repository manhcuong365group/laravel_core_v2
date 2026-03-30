<?php

namespace App\Data;

use Illuminate\Http\Request;

class NewsletterData extends BaseData
{
    public function __construct(
        public string $email,
        public ?string $name = null,
        public string $status = 'subscribed',
        public ?string $source = null,
    ) {
    }
}
