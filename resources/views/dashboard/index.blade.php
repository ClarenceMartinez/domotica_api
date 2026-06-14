<x-layouts.app title="Dashboard">

    <div id="app-dashboard"
         data-clients="{{ $clients->toJson() }}">
    </div>

    @vite(['resources/js/apps/dashboard.js'])

</x-layouts.app>
