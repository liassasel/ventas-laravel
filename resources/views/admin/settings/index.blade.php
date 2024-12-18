@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="mx-auto max-w-2xl">
    <h1 class="text-2xl font-semibold leading-6 text-white mb-6">System Settings</h1>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6">
            <div class="space-y-6">
                <div>
                    <label for="system_start_time" class="block text-sm font-medium text-white">System Start Time</label>
                    <input type="time" name="system_start_time" id="system_start_time" required 
                           value="{{ old('system_start_time', $settings->system_start_time) }}"
                           class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="system_end_time" class="block text-sm font-medium text-white">System End Time</label>
                    <input type="time" name="system_end_time" id="system_end_time" required 
                           value="{{ old('system_end_time', $settings->system_end_time) }}"
                           class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_system_active" id="is_system_active" value="1" 
                           {{ old('is_system_active', $settings->is_system_active) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_system_active" class="ml-2 block text-sm text-white">System Active</label>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Settings
                </button>
            </div>
        </div>
    </form>

    <div class="mt-8">
        <form action="{{ route('admin.settings.deactivateNonAdmins') }}" method="POST">
            @csrf
            <button type="submit" 
                    onclick="return confirm('Are you sure you want to deactivate all non-admin users?');"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Deactivate All Non-Admin Users
            </button>
        </form>
    </div>
</div>
@endsection

