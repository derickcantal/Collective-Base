<div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
    <nav class="overflow-x-auto bg-transparent dark:bg-transparent">
        <div class="max-w-screen-2xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
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
        </div>
    </nav>
</div>
