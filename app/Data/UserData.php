<?php

namespace App\Data;

use Illuminate\Http\Request;

class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password,
        public array $roles = [],
        public bool $is_active = true,
        public mixed $avatar = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            roles: $data['roles'] ?? [],
            is_active: (bool) ($data['is_active'] ?? true),
            avatar: $data['avatar'] ?? null,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
            roles: $request->input('roles', []),
            is_active: $request->boolean('is_active', true),
            avatar: $request->file('avatar'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        return $data;
    }
}
