@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="mx-auto max-w-2xl">
    <h1 class="text-2xl font-semibold leading-6 text-white mb-6">System Settings</h1>

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label for="system_start_time" class="block text-sm font-medium text-white">System Start Time</label>
            <input type="time" name="system_start_time" id="system_start_time" required value="{{ old('system_start_time', $settings->system_start_time) }}"
                   class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div>
            <label for="system_end_time" class="block text-sm font-medium text-white">System End Time</label>
            <input type="time" name="system_end_time" id="system_end_time" required value="{{ old('system_end_time', $settings->system_end_time) }}"
                   class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="is_system_active" id="is_system_active" value="1" {{ old('is_system_active', $settings->is_system_active) ? 'checked' : '' }}
                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="is_system_active" class="ml-2 block text-sm text-white">System Active</label>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update Settings
            </button>
        </div>
    </form>

    <div class="mt-8">
        <form action="{{ route('settings.deactivateNonAdmins') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    onclick="return confirm('Are you sure you want to deactivate all non-admin users?');">
                Deactivate All Non-Admin Users
            </button>
        </form>
    </div>
</div>
@endsection

