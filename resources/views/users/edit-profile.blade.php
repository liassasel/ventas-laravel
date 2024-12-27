@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold leading-6 text-white">Your Profile</h1>
            <p class="mt-2 text-sm text-gray-400">View your profile information and update your photo.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-500/10 border border-green-500/50 rounded-lg p-4">
            <p class="text-sm text-green-400">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 bg-red-500/10 border border-red-500/50 rounded-lg p-4">
            <ul class="list-disc list-inside text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-white/10 bg-gray-900/50 p-8">
        <!-- Profile Picture Section -->
        <form action="{{ route('users.update-profile') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div class="flex flex-col items-center space-y-4">
                <div class="relative group">
                    <img class="h-40 w-40 rounded-full object-cover border-4 border-gray-800 shadow-xl" 
                         src="{{ $user->profile_picture_url }}" 
                         alt="Current profile picture">
                    <label class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                        <span class="sr-only">Choose profile photo</span>
                        <input type="file" name="profile_picture" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                               onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])"/>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>
                </div>
                <img id="preview" class="h-40 w-40 rounded-full object-cover border-4 border-gray-800 shadow-xl hidden" alt="Preview"/>
                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Update Profile Picture
                </button>
            </div>
        </form>

        <!-- User Information (Read-only) -->
        <div class="mt-8 grid gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-400">Name</label>
                <p class="mt-1 text-white">{{ $user->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400">Email</label>
                <p class="mt-1 text-white">{{ $user->email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400">Username</label>
                <p class="mt-1 text-white">{{ $user->username }}</p>
            </div>

            @if($user->dni)
            <div>
                <label class="block text-sm font-medium text-gray-400">DNI</label>
                <p class="mt-1 text-white">{{ $user->dni }}</p>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-400">Role</label>
                <p class="mt-1 text-white">
                    @if($user->is_admin)
                        Administrator
                    @elseif($user->is_technician)
                        Technician
                    @else
                        User
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[type="file"]');
    const preview = document.getElementById('preview');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            preview.classList.remove('hidden');
            preview.src = URL.createObjectURL(this.files[0]);
        }
    });
});
</script>
@endpush
@endsection

