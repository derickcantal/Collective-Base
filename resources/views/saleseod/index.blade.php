<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('saleseod.index') }}" class="inline-flex items-center text-lg font-high text-white-700 hover:text-blue-600 dark:text-white dark:hover:text-gray-400"> END OF DAILY SALES</a></u>
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-8">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <!-- Breadcrumb -->
                                <nav class="flex px-5 py-3 text-gray-700  bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                        <li class="inline-flex items-center">
                                        <a href="{{ route('saleseod.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                            </svg>
                                            EOD
                                        </a>
                                        </li>
                                        <li>
                                        
                                    </ol>
                                </nav>
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


    
