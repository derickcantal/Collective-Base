<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <ul class="flex flex-wrap -mb-px inline-block p-4 border-b-4 border-transparent rounded-t-lg space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <x-nav-link :href="route('reportssales.index')" :active="request()->routeIs('reportssales.index')">
            {{ __('Sales') }}
        </x-nav-link>
        @if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor')
        <x-nav-link :href="route('reportstopsales.index')" :active="request()->routeIs('reportstopsales.index')">
            {{ __('Top Sales') }}
        </x-nav-link>
        @endif
        <x-nav-link :href="route('reportsrequests.index')" :active="request()->routeIs('reportsrequests.index')">
            {{ __('Requests') }}
        </x-nav-link>
        <x-nav-link :href="route('reportsrentals.index')" :active="request()->routeIs('reportsrentals.index')">
            {{ __('Rental Payments') }}
        </x-nav-link>
        <x-nav-link :href="route('reportsattendance.index')" :active="request()->routeIs('reportsattendance.index')">
            {{ __('Attendance') }}
        </x-nav-link>
    </ul>
</div>
