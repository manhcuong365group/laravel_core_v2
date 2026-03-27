<?php

namespace App\Traits;

use Illuminate\Validation\Rule;

trait WithUserForms
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $selectedRoles = [];
    public bool $is_active = true;
    public $avatar;

    /**
     * Common rules for Users.
     */
    protected function userRules(int $ignoreId = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                $ignoreId ? Rule::unique('users', 'email')->ignore($ignoreId) : 'unique:users,email',
            ],
            'password' => ($ignoreId ? 'nullable' : 'required') . '|string|min:8|confirmed',
            'selectedRoles' => 'array',
            'selectedRoles.*' => 'exists:roles,name',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|max:2048',
        ];
    }
}
