<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Casa Inteligente' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-800">

    <nav class="bg-gray-900 text-white px-6 py-4 flex items-center gap-8">
        <span class="font-bold text-lg tracking-wide">Casa Inteligente</span>
        <div class="flex gap-6 text-sm">
            <a href="{{ route('clientes.index') }}" class="hover:text-blue-300 transition-colors">Clientes</a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-8">

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot }}
    </main>

</body>
</html>
