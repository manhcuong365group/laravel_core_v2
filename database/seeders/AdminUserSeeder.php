<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Đảm bảo Role super-admin tồn tại
        $role = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);

        // 2. Tạo hoặc cập nhật user admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // 3. Gán Role
        $admin->assignRole($role);

        $this->command->info('✅ Đã tạo tài khoản Admin thành công!');
        $this->command->info('📧 Email: admin@gmail.com');
        $this->command->info('🔑 Password: 123456');
    }
}
