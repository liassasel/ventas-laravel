<!DOCTYPE html>
<html lang="en" class="h-full">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>
        @vite('resources/css/app.css')
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
<body class="h-full bg-black text-white" x-data="{ darkMode: true, sidebarOpen: false }" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')) || true; $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))">
    <div class="min-h-full" :class="{ 'dark': darkMode }">
        <!-- Sidebar -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 flex md:hidden" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>
            <div class="relative flex w-full max-w-xs flex-1 flex-col bg-black">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
                    <div class="flex flex-shrink-0 items-center px-4">
                        <img class="h-8 w-auto" src="https://assets.vercel.com/image/upload/v1607554385/repositories/vercel/logo.png" alt="Vercel">
                    </div>
                    <nav class="mt-5 space-y-1 px-2">
                        <a href="{{ route('products.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-900 hover:text-white">
                            <svg class="mr-4 h-6 w-6 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            Products
                        </a>
                        <a href="#" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-900 hover:text-white">
                            <svg class="mr-4 h-6 w-6 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Users
                        </a>
                    </nav>
                </div>
                <div class="flex flex-shrink-0 border-t border-gray-800 p-4">
                    <button @click="darkMode = !darkMode" class="flex-shrink-0 group block w-full">
                        <div class="flex items-center">
                            <div>
                                <svg x-show="!darkMode" class="h-6 w-6 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                <svg x-show="darkMode" class="h-6 w-6 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p x-text="darkMode ? 'Light Mode' : 'Dark Mode'" class="text-sm font-medium text-gray-400 group-hover:text-gray-300"></p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
            <div class="flex min-h-0 flex-1 flex-col bg-black">
                <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                    <div class="flex flex-shrink-0 items-center px-4">
                        <img class="h-8 w-auto" src="https://assets.vercel.com/image/upload/v1607554385/repositories/vercel/logo.png" alt="Vercel">
                    </div>
                    <nav class="mt-5 flex-1 space-y-1 bg-black px-2">
                        <a href="{{ route('products.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-900 hover:text-white">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            Products
                        </a>
                        <a href="{{ route('technical_services.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-900 hover:text-white">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="currentColor" d="M2 4.5V6h3.586a.5.5 0 0 0 .353-.146L7.293 4.5L5.939 3.146A.5.5 0 0 0 5.586 3H3.5A1.5 1.5 0 0 0 2 4.5m-1 0A2.5 2.5 0 0 1 3.5 2h2.086a1.5 1.5 0 0 1 1.06.44L8.207 4H12.5A2.5 2.5 0 0 1 15 6.5v2.585A1.5 1.5 0 0 0 14.5 9H14V6.5A1.5 1.5 0 0 0 12.5 5H8.207l-1.56 1.56A1.5 1.5 0 0 1 5.585 7H2v4.5A1.5 1.5 0 0 0 3.5 13h4.585a1.5 1.5 0 0 0 .297.5a1.5 1.5 0 0 0-.297.5H3.5A2.5 2.5 0 0 1 1 11.5zM9.5 10a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM9 14.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/></svg>
                            Technical services
                        </a>
                        <a href="{{ route('users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-900 hover:text-white">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Users
                        </a>
                        <a href="{{ route('settings.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-900 hover:text-white">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m10.135 21l-.362-2.892q-.479-.145-1.035-.454q-.557-.31-.947-.664l-2.668 1.135l-1.865-3.25l2.306-1.739q-.045-.27-.073-.558q-.03-.288-.03-.559q0-.252.03-.53q.028-.278.073-.626L3.258 9.126l1.865-3.212L7.771 7.03q.448-.373.97-.673q.52-.3 1.013-.464L10.134 3h3.732l.361 2.912q.575.202 1.016.463t.909.654l2.725-1.115l1.865 3.211l-2.382 1.796q.082.31.092.569t.01.51q0 .233-.02.491q-.019.259-.088.626l2.344 1.758l-1.865 3.25l-2.681-1.154q-.467.393-.94.673t-.985.445L13.866 21zM11 20h1.956l.369-2.708q.756-.2 1.36-.549q.606-.349 1.232-.956l2.495 1.063l.994-1.7l-2.189-1.644q.125-.427.166-.786q.04-.358.04-.72q0-.38-.04-.72t-.166-.747l2.227-1.683l-.994-1.7l-2.552 1.07q-.454-.499-1.193-.935q-.74-.435-1.4-.577L13 4h-1.994l-.312 2.689q-.756.161-1.39.52q-.633.358-1.26.985L5.55 7.15l-.994 1.7l2.169 1.62q-.125.336-.175.73t-.05.82q0 .38.05.755t.156.73l-2.15 1.645l.994 1.7l2.475-1.05q.589.594 1.222.953q.634.359 1.428.559zm.973-5.5q1.046 0 1.773-.727T14.473 12t-.727-1.773t-1.773-.727q-1.052 0-1.776.727T9.473 12t.724 1.773t1.776.727M12 12"/></svg>
                            System Settings
                        </a>
                    </nav>
                </div>
                <div class="flex flex-shrink-0 border-t border-gray-800 p-4">
                    <button @click="darkMode = !darkMode" class="flex-shrink-0 w-full group block">
                        <div class="flex items-center">
                            <div>
                                <svg x-show="!darkMode" class="h-6 w-6 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                <svg x-show="darkMode" class="h-6 w-6 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p x-text="darkMode ? 'Light Mode' : 'Dark Mode'" class="text-sm font-medium text-gray-400 group-hover:text-gray-300"></p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex flex-1 flex-col md:pl-64">
            <div class="sticky top-0 z-10 bg-gray-900 pl-1 pt-1 sm:pl-3 sm:pt-3 md:hidden">
                <button type="button" class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <main class="flex-1">
                <div class="py-6">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
                        <div class="border-b border-gray-800 pb-5 sm:flex sm:items-center sm:justify-between">
                            <h1 class="text-2xl font-semibold text-white">@yield('header')</h1>
                            <div class="mt-3 flex sm:mt-0 sm:ml-4">
                                <div class="ml-3 relative" x-data="{ open: false }">
                                    <div>
                                        <button @click="open = !open" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                            <span class="sr-only">Open user menu</span>
                                            <img class="h-8 w-8 rounded-full" src="https://i.pinimg.com/736x/6b/23/63/6b2363f7c94384e0be9f22e136a52b54.jpg" alt="">
                                        </button>
                                    </div>
                                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
                                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-2">
                                            Sign out
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(session('success'))
                            <div class="mt-4 rounded-md bg-green-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="mt-6">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('logout-form').addEventListener('submit', function(e) {
                e.preventDefault();
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/login';
                    } else {
                        console.error('Logout failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
        </script>
</body>
</html>

