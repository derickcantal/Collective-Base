<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <ul class="flex flex-wrap -mb-px inline-block p-4 border-b-4 border-transparent rounded-t-lg space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <x-nav-link :href="route('dashboardoverview.index')" :active="request()->routeIs('dashboardoverview.index')">
            {{ __('Summary') }}
        </x-nav-link>
        <x-nav-link :href="route('dashboardsales.index')" :active="request()->routeIs('dashboardsales.index')">
            {{ __('Sales') }}
        </x-nav-link>
        <x-nav-link :href="route('dashboardrequests.index')" :active="request()->routeIs('dashboardrequests.index')">
            {{ __('Requests') }}
        </x-nav-link>
        <x-nav-link :href="route('dashboardrentals.index')" :active="request()->routeIs('dashboardrentals.index')">
            {{ __('Rental Payments') }}
        </x-nav-link>
        <x-nav-link :href="route('dashboardattendance.index')" :active="request()->routeIs('dashboardattendance.index')">
            {{ __('Attendance') }}
        </x-nav-link>
    </ul>
</div>
