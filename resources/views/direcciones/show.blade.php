<x-layouts.app title="{{ $direccion->alias }}">

    @php
        $tipoLabels = [
            'luz'            => 'Luz',
            'persiana'       => 'Persiana',
            'sensor_puerta'  => 'Sensor de puerta',
            'sensor_ventana' => 'Sensor de ventana',
            'camara'         => 'Cámara',
            'calefaccion'    => 'Calefacción',
        ];
        $tipoIconos = [
            'luz'            => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
            'persiana'       => 'M4 6h16M4 10h16M4 14h16M4 18h16',
            'sensor_puerta'  => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'sensor_ventana' => 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2',
            'camara'         => 'M15 10l4.553-2.069A1 1 0 0121 8.82v6.362a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z',
            'calefaccion'    => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        ];
        $estadosActivos = ['encendido', 'abierto', 'activo'];
    @endphp

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

    @if ($direccion->dispositivos->isEmpty())
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-12 text-center">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <p class="text-sm text-gray-600">No hay dispositivos registrados en este inmueble.</p>
        </div>
    @else
        <div class="bg-[#111827] border border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dispositivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach ($direccion->dispositivos as $dispositivo)
                        @php $activo = in_array($dispositivo->estado, $estadosActivos); @endphp
                        <tr class="hover:bg-gray-800/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-800 border border-gray-700 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="{{ $tipoIconos[$dispositivo->tipo] ?? $tipoIconos['luz'] }}"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-white">{{ $dispositivo->nombre }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $tipoLabels[$dispositivo->tipo] ?? $dispositivo->tipo }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full
                                    {{ $activo
                                        ? 'bg-green-900/40 text-green-400 border border-green-800/40'
                                        : 'bg-gray-800 text-gray-500 border border-gray-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $activo ? 'bg-green-400' : 'bg-gray-600' }}"></span>
                                    {{ ucfirst($dispositivo->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('dispositivos.edit', $dispositivo) }}"
                                       class="text-xs text-blue-400 hover:text-blue-300 transition-colors">Editar</a>
                                    <form action="{{ route('dispositivos.destroy', $dispositivo) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar {{ $dispositivo->nombre }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-xs text-red-500 hover:text-red-400 transition-colors cursor-pointer">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-layouts.app>
