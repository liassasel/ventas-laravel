<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::firstOrCreate(
            ['id' => 1],
            [
                'system_start_time' => '08:00:00',
                'system_end_time' => '19:00:00',
                'is_system_active' => true,
            ]
        );
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'system_start_time' => 'required',
            'system_end_time' => 'required|after:system_start_time',
            'is_system_active' => 'boolean',
        ]);

        $settings = SystemSetting::firstOrCreate(['id' => 1]);
        $settings->update([
            'system_start_time' => $validatedData['system_start_time'],
            'system_end_time' => $validatedData['system_end_time'],
            'is_system_active' => $request->has('is_system_active'),
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'System settings updated successfully.');
    }

    public function deactivateNonAdmins()
    {
        User::where('is_admin', false)->update(['is_active' => false]);
        return redirect()->route('admin.settings.index')->with('success', 'All non-admin users have been deactivated.');
    }
}

