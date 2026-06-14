<x-layouts.app title="Clients">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-white">Clients</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manage system clients</p>
        </div>
        <a href="{{ route('clients.create') }}"
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New client
        </a>
    </div>

    <div class="bg-[#111827] border border-gray-800 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Properties</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse ($clients as $client)
                    <tr class="hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-white">{{ $client->name }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $client->email }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $client->phone ?? '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-900/50 text-blue-400 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-blue-800/50">
                                {{ $client->addresses_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('clients.show', $client) }}"
                                   class="text-xs text-gray-500 hover:text-gray-300 transition-colors">View</a>
                                <a href="{{ route('clients.edit', $client) }}"
                                   class="text-xs text-blue-400 hover:text-blue-300 transition-colors">Edit</a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ $client->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs text-red-500 hover:text-red-400 transition-colors cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-600">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            No clients registered.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($clients->hasPages())
        <div class="mt-4">{{ $clients->links() }}</div>
    @endif

</x-layouts.app>
