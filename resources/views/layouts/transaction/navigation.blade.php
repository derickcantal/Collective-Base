<div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
    <nav class="overflow-x-auto bg-transparent dark:bg-transparent">
        <div class="max-w-screen-2xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                    <x-nav-link :href="route('transactionsales.index')" :active="request()->routeIs('transactionsales.index')">
                        {{ __('Sales') }}
                    </x-nav-link>
                    <x-nav-link :href="route('transactionattendance.index')" :active="request()->routeIs('transactionattendance.index')">
                        {{ __('Attendance') }}
                    </x-nav-link>
                    @if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor')
                    <x-nav-link :href="route('transactionrental.index')" :active="request()->routeIs('transactionrental.index')">
                        {{ __('Rental Payment') }}
                    </x-nav-link>
                    @endif
                    <x-nav-link :href="route('transactionrentersales.index')" :active="request()->routeIs('transactionrentersales.index')">
                        {{ __('Renter Sales') }}
                    </x-nav-link>
                     @if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor')
                    <!-- <x-nav-link :href="route('transactionrequest.index')" :active="request()->routeIs('transactionrequest.index')">
                        {{ __('Request') }}
                    </x-nav-link> -->
                    <x-nav-link :href="route('transactioneod.index')" :active="request()->routeIs('transactioneod.index')">
                        {{ __('EOD') }}
                    </x-nav-link>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>

