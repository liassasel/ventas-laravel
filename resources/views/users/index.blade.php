@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold leading-6 text-white">Users</h1>
            <p class="mt-2 text-sm text-gray-400">Manage system users.</p>
        </div>
        <a href="{{ route('users.create') }}" class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
            Create User
        </a>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-0">Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Email</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Role</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($users as $user)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">{{ $user->name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $user->email }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                @if($user->is_admin)
                                    Admin
                                @elseif($user->is_technician)
                                    Technician
                                @elseif($user->is_seller)
                                Seller
                                @else
                                    User
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                <div class="flex space-x-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-400 hover:text-indigo-300">Edit</a>
                                    <form action="{{ route('users.toggleActive', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $user->is_active ? 'text-red-400 hover:text-red-300' : 'text-green-400 hover:text-green-300' }}">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(auth()->user()->is_admin)
    <div class="mt-8 flex justify-center space-x-4">
        <form action="{{ route('users.deactivateNonAdmins') }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate all non-admin users?');">
            @csrf
            <button type="submit" class="rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500">
                Deactivate All Non-Admin Users
            </button>
        </form>

        <form action="{{ route('users.activateNonAdmins') }}" method="POST" onsubmit="return confirm('Are you sure you want to activate all non-admin users?');">
            @csrf
            <button type="submit" class="rounded-md bg-green-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500">
                Activate All Non-Admin Users
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
