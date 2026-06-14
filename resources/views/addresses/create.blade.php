<x-layouts.app title="New property">

    <div class="mb-6">
        <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to {{ $client->name }}
        </a>
        <h2 class="text-xl font-bold text-white mt-3">New property</h2>
    </div>

    <div class="bg-[#111827] border border-gray-800 rounded-xl p-6 max-w-lg">
        <form action="{{ route('clients.addresses.store', $client) }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="alias" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Alias</label>
                <input type="text" id="alias" name="alias" value="{{ old('alias') }}" autofocus
                       placeholder="E.g.: Home, Office, Store"
                       class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              {{ $errors->has('alias') ? 'border-red-600' : 'border-gray-700' }}">
                @error('alias')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="street" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Street address</label>
                <input type="text" id="street" name="street" value="{{ old('street') }}"
                       placeholder="E.g.: 123 Main St, Buenos Aires"
                       class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              {{ $errors->has('street') ? 'border-red-600' : 'border-gray-700' }}">
                @error('street')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="plan_id" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Plan</label>
                <select id="plan_id" name="plan_id"
                        class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                               {{ $errors->has('plan_id') ? 'border-red-600' : 'border-gray-700' }}">
                    <option value="" class="text-gray-600">Select a plan</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} — up to {{ $plan->device_limit }} devices
                        </option>
                    @endforeach
                </select>
                @error('plan_id')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="border border-gray-800 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">Coordinates <span class="text-gray-600 normal-case font-normal">(optional — copy from Google Maps)</span></p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs text-gray-500 mb-1.5">Latitude</label>
                        <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}"
                               placeholder="-34.6037"
                               class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                                      {{ $errors->has('latitude') ? 'border-red-600' : 'border-gray-700' }}">
                        @error('latitude')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="longitude" class="block text-xs text-gray-500 mb-1.5">Longitude</label>
                        <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}"
                               placeholder="-58.3816"
                               class="w-full bg-gray-800 border rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                                      {{ $errors->has('longitude') ? 'border-red-600' : 'border-gray-700' }}">
                        @error('longitude')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-800">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Save property
                </button>
                <a href="{{ route('clients.show', $client) }}"
                   class="text-sm text-gray-500 hover:text-gray-300 px-3 py-2.5 transition-colors">Cancel</a>
            </div>
        </form>
    </div>

</x-layouts.app>
