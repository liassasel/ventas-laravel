<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    public function run()
    {
        DB::table('system_settings')->insert([
            'system_start_time' => '08:00:00',
            'system_end_time' => '19:00:00',
            'is_system_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

