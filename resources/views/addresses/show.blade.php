<x-layouts.app title="{{ $address->alias }}">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <a href="{{ route('clients.show', ['client' => $address->client_id]) }}"
               class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ $address->client->name }}
            </a>
            <h2 class="text-xl font-bold text-white mt-3">{{ $address->alias }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $address->street }}</p>
        </div>
        <a href="{{ route('addresses.edit', $address) }}"
           class="flex items-center gap-2 border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit property
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Plan</p>
            <p class="text-sm font-semibold text-white">{{ $address->plan->name }}</p>
        </div>
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Devices</p>
            <p class="text-sm font-semibold text-white">
                {{ $used }}
                <span class="text-gray-500 font-normal">/ {{ $limit }}</span>
            </p>
        </div>
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Coordinates</p>
            <p class="text-sm font-semibold text-white">
                {{ $address->latitude ? $address->latitude.', '.$address->longitude : '—' }}
            </p>
        </div>
    </div>

    {{-- Devices header --}}
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-white">Devices</h3>
        @if ($used < $limit)
            <a href="{{ route('addresses.devices.create', $address) }}"
               class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add device
            </a>
        @else
            <span class="text-xs text-gray-600 bg-gray-800 px-3 py-2 rounded-lg">Plan limit reached</span>
        @endif
    </div>

    {{-- Vue devices panel --}}
    <div id="app-devices" data-address-id="{{ $address->id }}"></div>

    @vite(['resources/js/apps/devices.js'])

</x-layouts.app>
