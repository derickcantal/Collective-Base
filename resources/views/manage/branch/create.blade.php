<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.manage.navigation')
        </div>
    </div>
    <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <form action="{{ route('managebranch.store') }}" method="POST">
                    @csrf   
                    <!-- Breadcrumb -->
                    <nav class="flex px-5 py-3 text-gray-700 bg-white dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                            <a href="{{ route('managebranch.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                </svg>
                                Branch 
                            </a>
                            </li>
                            <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Create New Branch</span>
                            </div>
                            </li>
                        </ol>
                    </nav>
                    <!-- Error & Success Notification -->
                    @include('layouts.notifications')   
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg dark:bg-gray-800">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Branch Information
                            </h3>
                        </div>
                        <!-- Modal body -->
                            <div class="grid gap-4 mb-4 grid-cols-2">
                                <div class="col-span-2 sm:col-span-1 ">
                                    <!-- nn -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="branchname" :value="__('Branch Name')" />
                                        <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" :value="old('branchname')" required autofocus autocomplete="off" />
                                        <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- nn -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="branchaddress" :value="__('Address')" />
                                        <x-text-input id="branchaddress" class="block mt-1 w-full" type="text" name="branchaddress" :value="old('branchaddress')" required autofocus autocomplete="off" />
                                        <x-input-error :messages="$errors->get('branchaddress')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- nn -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="branchcontact" :value="__('Contact No.')" />
                                        <x-text-input id="branchcontact" class="block mt-1 w-full" type="text" name="branchcontact" :value="old('branchcontact')" required autofocus autocomplete="off" />
                                        <x-input-error :messages="$errors->get('branchcontact')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- nn -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="branchemail" :value="__('Email')" />
                                        <x-text-input id="branchemail" class="block mt-1 w-full" type="email" name="branchemail" :value="old('branchemail')" required autocomplete="email" />
                                        <x-input-error :messages="$errors->get('branchemail')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                        <!-- nn -->
                                        <div class="form-group mt-4">
                                        <x-input-label for="cabinetcount" :value="__('Cabinet Count')" />
                                        <x-text-input id="cabinetcount" class="block mt-1 w-full" type="number" name="cabinetcount" :value="old('cabinetcount')" required autofocus autocomplete="off" />
                                        <x-input-error :messages="$errors->get('cabinetcount')" class="mt-2" />
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between col-span-2 sm:col-span-2">
                                    
                                    <x-primary-button class="ms-4">
                                        <a class="btn btn-primary" > Create</a>
                                    </x-primary-button>
                                    </div>
                                </div>
                                
                            </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>