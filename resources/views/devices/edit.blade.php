<x-layouts.app title="Edit device">

    <div class="mb-6">
        <a href="{{ route('addresses.show', $device->address_id) }}"
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back
        </a>
        <h2 class="text-xl font-bold text-white mt-3">Edit device</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ $device->name }}</p>
    </div>

    <div class="bg-[#111827] border border-gray-800 rounded-xl p-6 max-w-lg">
        <form action="{{ route('devices.update', $device) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">
                    Custom name
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $device->name) }}"
                       class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              {{ $errors->has('name') ? 'border-red-600' : 'border-gray-700' }}">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-3">Device type</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($types as $value => $label)
                        @php $selected = old('type', $device->type) === $value; @endphp
                        <label class="relative flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors
                                      {{ $selected ? 'border-blue-600 bg-blue-900/20' : 'border-gray-700 bg-gray-800 hover:border-gray-600' }}">
                            <input type="radio" name="type" value="{{ $value }}"
                                   {{ $selected ? 'checked' : '' }}
                                   class="accent-blue-500 shrink-0"
                                   onchange="this.closest('.grid').querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-900/20')); this.closest('label').classList.add('border-blue-600','bg-blue-900/20')">
                            <span class="text-sm text-gray-300">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('type')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-800">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Save changes
                </button>
                <a href="{{ route('addresses.show', $device->address_id) }}"
                   class="text-sm text-gray-500 hover:text-gray-300 px-3 py-2.5 transition-colors">Cancel</a>
            </div>
        </form>
    </div>

</x-layouts.app>
