<x-layouts.app title="Editar cliente">

    <div class="mb-6">
        <a href="{{ route('clientes.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
        <h2 class="text-xl font-bold text-white mt-3">Editar cliente</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ $cliente->nombre }}</p>
    </div>

    <div class="bg-[#111827] border border-gray-800 rounded-xl p-6 max-w-lg">
        <form action="{{ route('clientes.update', $cliente) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nombre" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                       class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              {{ $errors->has('nombre') ? 'border-red-600' : 'border-gray-700' }}">
                @error('nombre')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email', $cliente->email) }}"
                       class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              {{ $errors->has('email') ? 'border-red-600' : 'border-gray-700' }}">
                @error('email')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="telefono" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">
                    Teléfono <span class="text-gray-600 normal-case font-normal">(opcional)</span>
                </label>
                <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-800">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Guardar cambios
                </button>
                <a href="{{ route('clientes.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-300 px-3 py-2.5 transition-colors">Cancelar</a>
            </div>
        </form>
    </div>

</x-layouts.app>
