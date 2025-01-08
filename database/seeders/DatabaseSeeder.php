<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;
use App\Models\SystemSetting;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create system settings if they don't exist
        SystemSetting::firstOrCreate(
            ['id' => 1],
            [
                'system_start_time' => '08:00:00',
                'system_end_time' => '19:00:00',
                'is_system_active' => true,
            ]
        );
        
        // Create default category
        Category::firstOrCreate(['name' => 'Uncategorized']);

        // Create admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'username' => 'AdminAbel',
                'password' => bcrypt('password'),
                'is_admin' => true,
                'is_technician' => true,
                'is_active' => true,
                'is_seller' => true,
                'can_add_products' => true,
            ]
        );
    }
}

