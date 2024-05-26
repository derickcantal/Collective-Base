<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('saleseod.index') }}"> END OF DAILY SALES</a>
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-8">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <!-- Error & Success Notification -->
                                @include('layouts.notifications') 

                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Cashier Password Confirmation
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                    <form action="{{ route('saleseod.create') }}" method="POST" class="p-4 md:p-5">
                                    @csrf
                                    @method('GET')
                                        <div class="grid gap-4 mb-4 grid-cols-2">
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- branchname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="password" :value="__('Password')" />
                                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"  required autofocus autocomplete="off" /> 
                                                    
                                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                </div>
                                            </div>

                                            
                                        </div>
                                        <div class="col-span-2 sm:col-span-1 ">
                                                <div class="flex items-center justify-between col-span-2 sm:col-span-1">
                                                    <x-primary-button class="ms-4">
                                                        <a class="btn btn-primary" > Confirm Password</a>
                                                    </x-primary-button>
                                                </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>


    
