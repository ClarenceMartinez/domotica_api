<x-layouts.app title="{{ $cliente->nombre }}">

    <div class="flex items-start justify-between mb-6">
        <div>
            <a href="{{ route('clientes.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
            <h2 class="text-xl font-bold text-white mt-3">{{ $cliente->nombre }}</h2>
        </div>
        <a href="{{ route('clientes.edit', $cliente) }}"
           class="flex items-center gap-2 border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar
        </a>
    </div>

    {{-- Info cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Email</p>
            <p class="text-sm font-medium text-white">{{ $cliente->email }}</p>
        </div>
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Teléfono</p>
            <p class="text-sm font-medium text-white">{{ $cliente->telefono ?? '—' }}</p>
        </div>
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Cliente desde</p>
            <p class="text-sm font-medium text-white">{{ $cliente->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    {{-- Inmuebles --}}
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-white">Inmuebles</h3>
        <a href="{{ route('clientes.direcciones.create', $cliente) }}"
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Agregar inmueble
        </a>
    </div>

    @if ($cliente->direcciones->isEmpty())
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-12 text-center">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <p class="text-sm text-gray-600">Este cliente no tiene inmuebles registrados.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($cliente->direcciones as $direccion)
                <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="font-semibold text-white">{{ $direccion->alias }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $direccion->calle }}</p>
                        </div>
                        <span class="bg-blue-900/40 text-blue-400 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-blue-800/40 shrink-0 ml-3">
                            {{ $direccion->plan->nombre }}
                        </span>
                    </div>

                    @if ($direccion->latitud)
                        <p class="text-xs text-gray-600 mb-3">
                            {{ $direccion->latitud }}, {{ $direccion->longitud }}
                        </p>
                    @endif

                    <div class="flex items-center gap-4 pt-3 border-t border-gray-800">
                        <a href="{{ route('direcciones.show', $direccion) }}"
                           class="text-xs text-gray-400 hover:text-white transition-colors">Ver dispositivos</a>
                        <a href="{{ route('direcciones.edit', $direccion) }}"
                           class="text-xs text-blue-400 hover:text-blue-300 transition-colors">Editar</a>
                        <form action="{{ route('direcciones.destroy', $direccion) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar el inmueble {{ $direccion->alias }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-red-500 hover:text-red-400 transition-colors cursor-pointer">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-layouts.app>
