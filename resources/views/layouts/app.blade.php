<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="h-full bg-black" x-data="{ sidebarOpen: true }" :class="{ 'overflow-hidden': sidebarOpen }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="flex w-64 flex-col" 
               :class="{ '-translate-x-full': !sidebarOpen }"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">
            <div class="flex h-16 flex-shrink-0 items-center border-b border-gray-800 px-4">
                <svg class="h-8 w-8 text-white" viewBox="0 0 76 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M37.5274 0L75.0548 65H0L37.5274 0Z" fill="currentColor"/>
                </svg>
                <span class="ml-2 text-xl font-semibold text-white">Dashboard</span>
            </div>
            <div class="flex flex-1 flex-col overflow-y-auto bg-black">
                <nav class="flex-1 space-y-1 px-2 py-4">
                    <a href="{{ route('products.index') }}" class="group flex items-center rounded-md px-2 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Products
                    </a>
                    <!-- Add more menu items as needed -->
                </nav>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="flex h-16 flex-shrink-0 items-center border-b border-gray-800 bg-black px-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex flex-1 justify-between px-4">
                    <div class="flex flex-1">
                        <h1 class="text-2xl font-semibold text-white">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto bg-black p-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

