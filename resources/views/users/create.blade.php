@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold leading-6 text-white">Create User</h1>
            <p class="mt-2 text-sm text-gray-400">Add a new user to the system.</p>
        </div>
        <a href="{{ route('users.index') }}" class="text-sm text-gray-400 hover:text-white">
            Back to Users
        </a>
    </div>

    @if ($errors->any())
        <div class="mt-4 bg-red-500/10 border border-red-500/50 rounded-lg p-4">
            <ul class="list-disc list-inside text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" class="mt-8">
        @csrf
        <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6">
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-white">Name</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter user name">
                    </div>
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium leading-6 text-white">Username</label>
                    <div class="mt-2">
                        <input type="text" name="username" id="username" required value="{{ old('username') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter username">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-white">Email</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter user email">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-white">Password</label>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter user password">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium leading-6 text-white">Confirm Password</label>
                    <div class="mt-2">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Confirm user password">
                    </div>
                </div>

                <div>
                    <label for="dni" class="block text-sm font-medium leading-6 text-white">DNI (Optional)</label>
                    <div class="mt-2">
                        <input type="text" name="dni" id="dni" value="{{ old('dni') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter DNI (optional)">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_admin" class="ml-2 block text-sm text-white">Is Admin</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_technician" id="is_technician" value="1" {{ old('is_technician') ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_technician" class="ml-2 block text-sm text-white">Is Technician</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 block text-sm text-white">Is Active</label>
                </div>
            </div>

            <div class<div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('users.index') }}"
                   class="rounded-md px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-black shadow-sm transition-all hover:bg-gray-200">
                    Create User
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

