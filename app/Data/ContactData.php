<?php

namespace App\Data;

use Illuminate\Http\Request;

class ContactData
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $subject = null,
        public ?string $message = null,
        public ?string $status = null,
        public ?string $admin_notes = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            subject: $data['subject'] ?? null,
            message: $data['message'] ?? null,
            status: $data['status'] ?? null,
            admin_notes: $data['admin_notes'] ?? null,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            phone: $request->input('phone'),
            subject: $request->input('subject'),
            message: $request->input('message'),
            status: $request->input('status'),
            admin_notes: $request->input('admin_notes'),
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => $this->status,
            'admin_notes' => $this->admin_notes,
        ], fn($value) => !is_null($value));
    }
}
