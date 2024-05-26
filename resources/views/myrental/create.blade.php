  
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('myrental.index') }}"> My Rent</a></u> / {{ __('Make New Payment') }}
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <form action="{{ route('myrental.store') }}" method="POST" class="p-4 md:p-5">
                        @csrf   
                              
                            <div class="relative p-4 w-full max-w-full max-h-full">
                                <!-- Error & Success Notification -->
                                @include('layouts.notifications') 
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Rental Payment Information
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                        <div class="grid gap-4 mb-4 grid-cols-2">
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- branchname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="branchname" :value="__('Branch Name')" />
                                                    <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" value="{{ auth()->user()->branchname }}" required autofocus autocomplete="off" readonly/> 
                                                   
                                                    <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1 ">
                                                <!-- cabname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="cabinetname" :value="__('Cabinet No.')" />
                                                    <x-text-input id="cabinetname" class="block mt-1 w-full" type="text" name="cabinetname" value="{{ auth()->user()->cabinetname }}" required autofocus autocomplete="off" readonly/>
                                                    <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- username -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="username" :value="__('Username')" />
                                                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" value="{{ auth()->user()->username }}" required autofocus autocomplete="off" readonly/>
                                                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- firstname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="firstname" :value="__('First Name')" />
                                                    <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" value="{{ auth()->user()->firstname }}" required autofocus autocomplete="given-name" readonly/>
                                                    <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                                </div>
                                            </div>
                                   
                                            <div class="col-span-2 sm:col-span-1">
                                                    <!-- lastname -->
                                                    <div class="form-group mt-4">
                                                    <x-input-label for="lastname" :value="__('Last Name')" />
                                                    <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" value="{{ auth()->user()->lastname }}" required autofocus autocomplete="family-name" readonly/>
                                                    <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1 ">
                                                <!-- rpnotes -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="rpnotes" :value="__('Notes')" />
                                                    <x-text-input id="rpnotes" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" name="rpnotes" value="Details" required autofocus autocomplete="off"/>
                                                    <x-input-error :messages="$errors->get('rpnotes')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                    <!-- rpamount -->
                                                    <div class="form-group mt-4">
                                                    <x-input-label for="rpamount" :value="__('Rental Amount')" />
                                                    <x-text-input id="rpamount" class="block mt-1 w-full" type="text" name="rpamount" value="0.00" required autofocus autocomplete="off" readonly/>
                                                    <x-input-error :messages="$errors->get('rpamount')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                    <!-- rppaytype -->
                                                
                                                    <div class="form-group mt-4">
                                                    <x-input-label for="rppaytype" :value="__('Payment Mode')" />
                                                    <select id="rppaytype" name="rppaytype" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('rppaytype')" >
                                                        <option value = "Cash">Cash</option>
                                                        <option value = "Bank Transfer">Bank Transfer</option>
                                                    </select>
                                                    
                                                    <x-input-error :messages="$errors->get('rppaytype')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- rpmonthyear -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="rpmonth" :value="__('Applicable Month')" />
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
                                                        <option value = "2023">2023</option>
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