<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — Casa Inteligente</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0d1117] text-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-sm px-4">

        {{-- Brand --}}
        <div class="flex flex-col items-center mb-8 gap-3">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <div class="text-center leading-tight">
                <p class="text-xs text-gray-400 font-medium tracking-widest uppercase">Casa</p>
                <p class="text-xl font-bold text-white tracking-wide">Inteligente</p>
            </div>
        </div>

        {{-- Card --}}
        <div class="bg-[#111827] border border-gray-800 rounded-2xl p-8">

            <h2 class="text-base font-semibold text-white mb-6">Acceso al sistema</h2>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-400 mb-1.5">
                        Correo electrónico
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full bg-[#0d1117] border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                               placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent
                               @error('email') border-red-500 @enderror"
                        placeholder="admin@casainteligente.com"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-400 mb-1.5">
                        Contraseña
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full bg-[#0d1117] border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                               placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent
                               @error('password') border-red-500 @enderror"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4 h-4 rounded border-gray-700 bg-[#0d1117] text-blue-600 focus:ring-blue-600 focus:ring-offset-0"
                    >
                    <label for="remember" class="text-xs text-gray-400">Recordarme</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded-lg
                           transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-[#111827]"
                >
                    Iniciar sesión
                </button>

            </form>

        </div>

        <p class="text-center text-xs text-gray-600 mt-6">© 2024 Casa Inteligente</p>

    </div>

</body>
</html>
