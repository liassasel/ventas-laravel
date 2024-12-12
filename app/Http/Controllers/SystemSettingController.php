<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::first();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'system_start_time' => 'required|date_format:H:i',
            'system_end_time' => 'required|date_format:H:i|after:system_start_time',
            'is_system_active' => 'boolean',
        ]);

        $settings = SystemSetting::first();
        $settings->update($validatedData);

        return redirect()->route('settings.index')->with('success', 'System settings updated successfully.');
    }

    public function deactivateNonAdmins()
    {
        User::where('is_admin', false)->update(['is_active' => false]);
        return redirect()->route('settings.index')->with('success', 'All non-admin users have been deactivated.');
    }
}

