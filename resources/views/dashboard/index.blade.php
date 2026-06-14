<x-layouts.app title="Dashboard">

    <div id="app-dashboard"
         data-clientes="{{ $clientes->toJson() }}">
    </div>

    @vite(['resources/js/apps/dashboard.js'])

</x-layouts.app>
