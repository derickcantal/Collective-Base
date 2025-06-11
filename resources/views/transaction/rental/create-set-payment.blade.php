<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.transaction.navigation')
        </div>
    </div>
	<div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <form action="{{ route('transactionrental.storesetpayment') }}" enctype="multipart/form-data" method="get">
                    @csrf
                    <!-- Breadcrumb -->
                    <nav class="flex px-5 py-3 text-gray-700 bg-white dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                            <a href="{{ route('transactionrental.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                </svg>
                                Rental Payments
                            </a>
                            </li>
                            <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('transactionrental.setpayment') }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">
                                    Create 
                                </a>
                            </div>
                            </li>
                            <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Set Rental Month/Year </span>
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
                                Set Month for Payment
                            </h3>
                        </div>
                        
                        
                        <!-- Modal body -->
                        <div class="grid gap-4 mb-4 grid-cols-2">
                                
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- rpmonthyear -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="rpmonth" :value="__('Active: Applicable Month')" />
                                        <x-text-input id="rpmonth" name="rpmonth" class="mt-1 w-1/2" type="text" :value="old('rpmonth',$cabinet->rpmonth)" readonly required/>
                                        <x-input-error :messages="$errors->get('rpmonth')" class="mt-2" />

                                        <x-text-input id="rpyear" name="rpyear" type="text" class="mt-1 w-auto" :value="old('rpyear',$cabinet->rpyear)" readonly required/>
                                        <x-input-error :messages="$errors->get('rpyear')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <!-- rpmonthyear -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="rpmonth" :value="__('New: Applicable Month')" />
                                        <select id="rpmonth" name="rpmonth" class="form-select mt-1  border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('rpmonth')">
                                            <option value = "01">01</option>
                                            <option value = "02">02</option>
                                            <option value = "03">03</option>
                                            <option value = "04">04</option>
                                            <option value = "05">05</option>
                                            <option value = "06">06</option>
                                            <option value = "07">07</option>
                                            <option value = "08">08</option>
                                            <option value = "09">09</option>
                                            <option value = "10">10</option>
                                            <option value = "11">11</option>
                                            <option value = "12">12</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('rpmonth')" class="mt-2" />
                                    
                                        <select id="rpyear" name="rpyear" class="form-select mt-1  border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('rpyear')">
                                            <option value = "2024">2024</option>
                                            <option value = "2025">2025</option>
                                            <option value = "2026">2026</option>
                                            <option value = "2027">2027</option>
                                            <option value = "2028">2028</option>
                                            <option value = "2029">2029</option>
                                            <option value = "2030">2030</option>
                                            <option value = "2031">2031</option>
                                            <option value = "2032">2032</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('rpyear')" class="mt-2" />
                                    </div>
                                </div>

                            
                                
                                <div class="flex items-center justify-between col-span-2 sm:col-span-2">
                                    
                                    <x-primary-button class="ms-4">
                                        <a class="btn btn-primary" > Save</a>
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