<div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
    <nav class="overflow-x-auto bg-transparent dark:bg-transparent">
        <div class="max-w-screen-2xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                    <x-nav-link :href="route('dashboardoverview.index')" :active="request()->routeIs('dashboardoverview.index')">
                        {{ __('Summary') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboardsales.index')" :active="request()->routeIs('dashboardsales.index')">
                        {{ __('Sales') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboardrentals.index')" :active="request()->routeIs('dashboardrentals.index')">
                        {{ __('Rental Payments') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboardattendance.index')" :active="request()->routeIs('dashboardattendance.index')">
                        {{ __('Attendance') }}
                    </x-nav-link>

                </ul>
            </div>
        </div>
    </nav>
</div>
