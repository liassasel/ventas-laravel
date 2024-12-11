<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create default category
        Category::firstOrCreate(['name' => 'Uncategorized']);

        // Create admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'is_admin' => true,
                'is_technician' => true,
                'is_active' => true,

            ]
        );
    }
}

