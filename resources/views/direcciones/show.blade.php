<x-layouts.app title="{{ $direccion->alias }}">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <a href="{{ route('clientes.show', ['cliente' => $direccion->cliente_id]) }}"
               class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ $direccion->cliente->nombre }}
            </a>
            <h2 class="text-xl font-bold text-white mt-3">{{ $direccion->alias }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $direccion->calle }}</p>
        </div>
        <a href="{{ route('direcciones.edit', $direccion) }}"
           class="flex items-center gap-2 border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar inmueble
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Plan</p>
            <p class="text-sm font-semibold text-white">{{ $direccion->plan->nombre }}</p>
        </div>
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Dispositivos</p>
            <p class="text-sm font-semibold text-white">
                {{ $usados }}
                <span class="text-gray-500 font-normal">/ {{ $limite }}</span>
            </p>
        </div>
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Coordenadas</p>
            <p class="text-sm font-semibold text-white">
                {{ $direccion->latitud ? $direccion->latitud.', '.$direccion->longitud : '—' }}
            </p>
        </div>
    </div>

    {{-- Dispositivos --}}
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-white">Dispositivos</h3>
        @if ($usados < $limite)
            <a href="{{ route('direcciones.dispositivos.create', $direccion) }}"
               class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar dispositivo
            </a>
        @else
            <span class="text-xs text-gray-600 bg-gray-800 px-3 py-2 rounded-lg">
                Límite del plan alcanzado
            </span>
        @endif
    </div>

    <div id="app-luces" data-direccion-id="{{ $direccion->id }}"></div>

    @vite(['resources/js/apps/luces.js'])

</x-layouts.app>
