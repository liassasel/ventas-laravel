<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::first();
        
        if (!$settings) {
            $settings = SystemSetting::create([
                'system_start_time' => '08:00:00',
                'system_end_time' => '19:00:00',
                'is_system_active' => true,
            ]);
        }

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'system_start_time' => 'required',
            'system_end_time' => 'required|after:system_start_time',
            'is_system_active' => 'boolean',
        ]);

        $settings = SystemSetting::first();
        
        if (!$settings) {
            $settings = new SystemSetting();
        }

        $settings->system_start_time = $validatedData['system_start_time'];
        $settings->system_end_time = $validatedData['system_end_time'];
        $settings->is_system_active = $request->has('is_system_active');
        $settings->save();

        return redirect()->route('settings.index')->with('success', 'Configuración actualizada correctamente.');
    }
}

