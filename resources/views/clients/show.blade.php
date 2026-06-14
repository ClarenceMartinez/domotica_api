<x-layouts.app title="{{ $client->name }}">

    <div class="flex items-start justify-between mb-6">
        <div>
            <a href="{{ route('clients.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back
            </a>
            <h2 class="text-xl font-bold text-white mt-3">{{ $client->name }}</h2>
        </div>
        <a href="{{ route('clients.edit', $client) }}"
           class="flex items-center gap-2 border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Email</p>
            <p class="text-sm font-medium text-white">{{ $client->email }}</p>
        </div>
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Phone</p>
            <p class="text-sm font-medium text-white">{{ $client->phone ?? '—' }}</p>
        </div>
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1.5">Client since</p>
            <p class="text-sm font-medium text-white">{{ $client->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-white">Properties</h3>
        <a href="{{ route('clients.addresses.create', $client) }}"
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add property
        </a>
    </div>

    @if ($client->addresses->isEmpty())
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-12 text-center">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <p class="text-sm text-gray-600">This client has no registered properties.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($client->addresses as $address)
                <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="font-semibold text-white">{{ $address->alias }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $address->street }}</p>
                        </div>
                        <span class="bg-blue-900/40 text-blue-400 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-blue-800/40 shrink-0 ml-3">
                            {{ $address->plan->name }}
                        </span>
                    </div>

                    @if ($address->latitude)
                        <p class="text-xs text-gray-600 mb-3">
                            {{ $address->latitude }}, {{ $address->longitude }}
                        </p>
                    @endif

                    <div class="flex items-center gap-4 pt-3 border-t border-gray-800">
                        <a href="{{ route('addresses.show', $address) }}"
                           class="text-xs text-gray-400 hover:text-white transition-colors">View devices</a>
                        <a href="{{ route('addresses.edit', $address) }}"
                           class="text-xs text-blue-400 hover:text-blue-300 transition-colors">Edit</a>
                        <form action="{{ route('addresses.destroy', $address) }}" method="POST"
                              onsubmit="return confirm('Delete property {{ $address->alias }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-red-500 hover:text-red-400 transition-colors cursor-pointer">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-layouts.app>
