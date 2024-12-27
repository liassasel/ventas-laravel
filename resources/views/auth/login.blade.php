<!DOCTYPE html>
<html lang="en" class="h-full bg-black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="h-full">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 bg-gray-900/50 p-8 rounded-xl border border-white/10">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                    Iniciar sesión
                </h2>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="login" class="block text-sm font-medium text-white">Email o Nombre de Usuario</label>
                        <input id="login" name="login" type="text" required value="{{ old('login') }}"
                               class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
    
                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-white">Contraseña</label>
                        <input id="password" name="password" type="password" required
                               class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>
    
                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-black bg-white hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
