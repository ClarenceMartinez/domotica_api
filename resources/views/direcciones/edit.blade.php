<x-layouts.app title="Editar inmueble">

    <div class="mb-6">
        <a href="{{ route('clientes.show', ['cliente' => $direccion->cliente_id]) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
        <h2 class="text-xl font-bold text-white mt-3">Editar inmueble</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ $direccion->alias }}</p>
    </div>

    <div class="bg-[#111827] border border-gray-800 rounded-xl p-6 max-w-lg">
        <form action="{{ route('direcciones.update', $direccion) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="alias" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Alias</label>
                <input type="text" id="alias" name="alias" value="{{ old('alias', $direccion->alias) }}"
                       class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              {{ $errors->has('alias') ? 'border-red-600' : 'border-gray-700' }}">
                @error('alias')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="calle" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Dirección</label>
                <input type="text" id="calle" name="calle" value="{{ old('calle', $direccion->calle) }}"
                       class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              {{ $errors->has('calle') ? 'border-red-600' : 'border-gray-700' }}">
                @error('calle')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="plan_id" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Plan</label>
                <select id="plan_id" name="plan_id"
                        class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                               {{ $errors->has('plan_id') ? 'border-red-600' : 'border-gray-700' }}">
                    <option value="">Seleccioná un plan</option>
                    @foreach ($planes as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $direccion->plan_id) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->nombre }} — hasta {{ $plan->max_dispositivos }} dispositivos
                        </option>
                    @endforeach
                </select>
                @error('plan_id')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="border border-gray-800 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">Coordenadas <span class="text-gray-600 normal-case font-normal">(opcional — copiar de Google Maps)</span></p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="latitud" class="block text-xs text-gray-500 mb-1.5">Latitud</label>
                        <input type="text" id="latitud" name="latitud" value="{{ old('latitud', $direccion->latitud) }}"
                               placeholder="-34.6037"
                               class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                                      {{ $errors->has('latitud') ? 'border-red-600' : 'border-gray-700' }}">
                        @error('latitud')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="longitud" class="block text-xs text-gray-500 mb-1.5">Longitud</label>
                        <input type="text" id="longitud" name="longitud" value="{{ old('longitud', $direccion->longitud) }}"
                               placeholder="-58.3816"
                               class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                                      {{ $errors->has('longitud') ? 'border-red-600' : 'border-gray-700' }}">
                        @error('longitud')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-800">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Guardar cambios
                </button>
                <a href="{{ route('clientes.show', ['cliente' => $direccion->cliente_id]) }}"
                   class="text-sm text-gray-500 hover:text-gray-300 px-3 py-2.5 transition-colors">Cancelar</a>
            </div>
        </form>
    </div>

</x-layouts.app>
