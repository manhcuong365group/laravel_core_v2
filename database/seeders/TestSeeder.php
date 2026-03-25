<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $count = DB::table('admin_menus')->count();
        $this->command->info("Count: $count");
    }
}
