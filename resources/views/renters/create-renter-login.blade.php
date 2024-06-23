  
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('renters.index') }}" class="inline-flex items-center text-lg font-high text-white hover:text-blue-600 dark:text-white dark:hover:text-gray-400">Renters</a></u> | 
            <a href="{{ route('rentersrequests.index') }}" class="inline-flex items-center text-lg font-high text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">Renters Requests</a> |
            <a href="{{ route('rentalpayments.index') }}" class="inline-flex items-center text-lg font-high text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"> Rental Payments</a>  
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <form action="{{ route('renters.renterregister') }}" method="POST" class="p-4 md:p-5">
                        @csrf   
                            <!-- Error & Success Notification -->
                            @include('layouts.notifications') 
                            <div class="relative p-4 w-full max-w-full max-h-full">
                                
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Breadcrumb -->
                                    <nav class="flex px-5 py-3 text-gray-700  bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                            <li class="inline-flex items-center">
                                            <a href="{{ route('renters.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                                </svg>
                                                Renter
                                            </a>
                                            </li>
                                            <li aria-current="page">
                                            <div class="flex items-center">
                                                <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                                </svg>
                                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Renter Information</span>
                                            </div>
                                            </li>
                                            <li aria-current="page">
                                            <div class="flex items-center">
                                                <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                                </svg>
                                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">New</span>
                                            </div>
                                            </li>
                                            <li aria-current="page">
                                            <div class="flex items-center">
                                                <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                                </svg>
                                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Access Information</span>
                                            </div>
                                            </li>
                                        </ol>
                                    </nav>
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Renters Information
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                        <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2 sm:col-span-1">
                                                <!-- firstname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="firstname" :value="__('First Name')" />
                                                    <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname', $renterinfo->firstname)" required autofocus autocomplete="given-name" readonly/>
                                                    <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- middlename -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="middlename" :value="__('Middle Name')" />
                                                    <x-text-input id="middlename" class="block mt-1 w-full" type="text" name="middlename" :value="old('middlename', $renterinfo->middlename)" required autofocus autocomplete="additional-name" readonly/>
                                                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                    <!-- lastname -->
                                                    <div class="form-group mt-4">
                                                    <x-input-label for="lastname" :value="__('Last Name')" />
                                                    <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname', $renterinfo->lastname)" required autofocus autocomplete="family-name" readonly/>
                                                    <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- birthdate -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="birthdate" :value="__('Birth Date')" />
                                                    <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="old('birthdate', $renterinfo->birthdate)" required autofocus autocomplete="bday" readonly/>
                                                    <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1 ">
                                                <!-- username -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="username" :value="__('Username')" />
                                                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                                                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- Email Address -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="email" :value="__('Email')" />
                                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
                                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- lastname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="mobile_primary" :value="__('Mobile No. (1)')" />
                                                    <x-text-input id="mobile_primary" class="block mt-1 w-full" type="text" name="mobile_primary" :value="old('mobile_primary')" required />
                                                    <x-input-error :messages="$errors->get('mobile_primary')" class="mt-2" />
                                                </div>
                                            </div>

                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- lastname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="mobile_secondary" :value="__('Mobile No. (2)')" />
                                                    <x-text-input id="mobile_secondary" class="block mt-1 w-full" type="text" name="mobile_secondary" :value="old('mobile_secondary')" />
                                                    <x-input-error :messages="$errors->get('mobile_secondary')" class="mt-2" />
                                                </div>
                                            </div>

                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- lastname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="homeno" :value="__('Home No.')" />
                                                    <x-text-input id="homeno" class="block mt-1 w-full" type="text" name="homeno" :value="old('homeno')" />
                                                    <x-input-error :messages="$errors->get('homeno')" class="mt-2" />
                                                </div>
                                            </div>

                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- lastname -->
                                                <div class="form-group mt-4">
                                                    <x-text-input id="newrenter" class="block mt-1 w-full" type="hidden" name="newrenter" :value="old('newrenter','Y')" required readonly/>
                                                    <x-input-error :messages="$errors->get('newrenter')" class="mt-2" />
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
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>