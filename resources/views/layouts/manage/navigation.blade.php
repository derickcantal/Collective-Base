<div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
    <nav class="overflow-x-auto bg-transparent dark:bg-transparent">
        <div class="max-w-screen-2xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                    @if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor')
                    <x-nav-link :href="route('manageuser.index')" :active="request()->routeIs('manageuser.index')">
                        {{ __('Users') }}
                    </x-nav-link>
                    <x-nav-link :href="route('managerenter.index')" :active="request()->routeIs('managerenter.index')">
                        {{ __('Renters') }}
                    </x-nav-link>
                    @endif
                    @if(auth()->user()->accesstype == 'Cashier')
                    <x-nav-link :href="route('managecr.index')" :active="request()->routeIs('managecr.index')">
                        {{ __('Renters') }}
                    </x-nav-link>
                    @endif
                    @if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor')
                    <x-nav-link :href="route('managecabinet.index')" :active="request()->routeIs('managecabinet.index')">
                        {{ __('Cabinet') }}
                    </x-nav-link>
                    <x-nav-link :href="route('managebranch.index')" :active="request()->routeIs('managebranch.index')">
                        {{ __('Branch') }}
                    </x-nav-link>
                    <x-nav-link :href="route('managemailbox.index')" :active="request()->routeIs('managemailbox.index')">
                        {{ __('Mail Box') }}
                    </x-nav-link>
                    @endif

                </ul>
            </div>
        </div>
    </nav>
</div>
