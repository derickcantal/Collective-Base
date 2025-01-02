<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <ul class="flex flex-wrap -mb-px inline-block p-4 border-b-4 border-transparent rounded-t-lg space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <x-nav-link :href="route('manageuser.index')" :active="request()->routeIs('manageuser.index')">
            {{ __('Users') }}
        </x-nav-link>
        <x-nav-link :href="route('managerenter.index')" :active="request()->routeIs('managerenter.index')">
            {{ __('Renters') }}
        </x-nav-link>
        <x-nav-link :href="route('managecabinet.index')" :active="request()->routeIs('managecabinet.index')">
            {{ __('Cabinet') }}
        </x-nav-link>
        <x-nav-link :href="route('managebranch.index')" :active="request()->routeIs('managebranch.index')">
            {{ __('Branch') }}
        </x-nav-link>
        <x-nav-link :href="route('managemailbox.index')" :active="request()->routeIs('managemailbox.index')">
            {{ __('Mail Box') }}
        </x-nav-link>
    </ul>
</div>
