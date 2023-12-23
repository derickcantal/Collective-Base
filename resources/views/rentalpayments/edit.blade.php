<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('rentalpayments.index') }}"> Rental Payments</a></u> / {{ __('Process Rental Payments') }} / {{ $rentalPayments->cabinetname }}
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <form action="{{ route('rentalpayments.update',$rentalPayments->rpid) }}" enctype="multipart/form-data" method="POST" class="p-4 md:p-5">
                        @csrf
                        @method('PUT')   
                            <div class="relative p-4 w-full max-w-full max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Process Rental Payments 
                                        </h3>
                                    </div>
                                    
                                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                        <div class="max-w-xl">
                                            <div>
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('Proof Receipt') }}
                                            </h2>

                                            <img width="100" height="100" class="rounded-full mt-4" src="{{ asset("/storage/$rentalPayments->avatarproof") }}" alt="proof avatar" />

                                                <x-input-label for="name" value="Upload Receipt" />
                                                <x-text-input id="avatarproof" name="avatarproof" type="file"  class="mt-1 block w-full mt-3" :value="old('avatarproof', $rentalPayments->avatarproof)" autofocus autocomplete="off" />
                                                <x-input-error class="mt-2" :messages="$errors->get('avatarproof')" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- branchname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="branchname" :value="__('Branch Name')" />
                                                    <!-- <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" :value="old('branchname')" required autofocus autocomplete="off" /> -->
                                                    <select id="branchname" name="branchname" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('branchname')">
                                                        <option value = "CB Main">CB Main</option>
                                                        <option value = "CB Annex">CB Annex</option>
                                                        <option value = "CB Complex">CB Complex</option>
                                                        <option value = "CB Plus 1">CB Plus 1</option>
                                                        <option value = "CB Plus 2">CB Plus 2</option>
                                                        <option value = "CB Plus 3">CB Plus 3</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1 ">
                                                <!-- cabname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="cabinetname" :value="__('Cabinet No.')" />
                                                    <x-text-input id="cabinetname" class="block mt-1 w-full" type="text" name="cabinetname" :value="old('cabinetname', $rentalPayments->cabinetname)" required autofocus autocomplete="off" />
                                                    <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- firstname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="firstname" :value="__('First Name')" />
                                                    <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname', $rentalPayments->firstname)" required autofocus autocomplete="given-name" />
                                                    <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                                </div>
                                            </div>
                                   
                                            <div class="col-span-2 sm:col-span-1">
                                                    <!-- lastname -->
                                                    <div class="form-group mt-4">
                                                    <x-input-label for="lastname" :value="__('Last Name')" />
                                                    <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname', $rentalPayments->lastname)" required autofocus autocomplete="family-name" />
                                                    <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                    <!-- total sales -->
                                                    <div class="form-group mt-4">
                                                    <x-input-label for="totalsales" :value="__('Total Sales')" />
                                                    <x-text-input id="totalsales" class="block mt-1 w-full" type="text" name="totalsales" :value="old('totalsales', $rentalPayments->totalsales)" required autofocus autocomplete="off" />
                                                    <x-input-error :messages="$errors->get('totalsales')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                    <!-- total collected -->
                                                    <div class="form-group mt-4">
                                                    <x-input-label for="totalcollected" :value="__('Total Collected')" />
                                                    <x-text-input id="totalcollected" class="block mt-1 w-full" type="text" name="totalcollected" :value="old('totalcollected', $rentalPayments->totalcollected)" required autofocus autocomplete="off" />
                                                    <x-input-error :messages="$errors->get('totalcollected')" class="mt-2" />
                                                </div>
                                            </div>

                                            
                                            <div class="col-span-2 sm:col-span-1 ">
                                                <!-- Notes -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="rnotes" :value="__('Notes')" />
                                                    <x-text-input id="rnotes" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="textarea" name="rnotes" :value="old('rnotes', $rentalPayments->rnotes)" required autofocus autocomplete="off" />
                                                    <x-input-error :messages="$errors->get('rnotes')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between col-span-2 sm:col-span-2">
                                                
                                                <x-primary-button class="ms-4">
                                                    <a class="btn btn-primary" > Update</a>
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
   
