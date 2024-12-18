<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <ul class="flex flex-wrap -mb-px inline-block p-4 border-b-4 border-transparent rounded-t-lg space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <x-nav-link :href="route('transactionsales.index')" :active="request()->routeIs('transactionsales.index')">
            {{ __('Sales') }}
        </x-nav-link>
        <x-nav-link :href="route('transactionattendance.index')" :active="request()->routeIs('transactionattendance.index')">
            {{ __('Attendance') }}
        </x-nav-link>
        <x-nav-link :href="route('transactionrental.index')" :active="request()->routeIs('transactionrental.index')">
            {{ __('Rental Payment') }}
        </x-nav-link>
        <x-nav-link :href="route('transactionrequest.index')" :active="request()->routeIs('transactionrequest.index')">
            {{ __('Request') }}
        </x-nav-link>
        <x-nav-link :href="route('transactioneod.index')" :active="request()->routeIs('transactioneod.index')">
            {{ __('EOD') }}
        </x-nav-link>
    </ul>
</div>
