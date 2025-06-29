<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.transaction.navigation')
        </div>
    </div>
	<div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <form action="{{ route('transactionremittance.createdetails', [$branch->branchid,$renter->rentersid,$cabinet->cabid]) }}" enctype="multipart/form-data" method="POST">
                    @csrf   
                    @method('GET')
                    <!-- Breadcrumb -->
                    <nav class="flex px-5 py-3 text-gray-700 bg-white dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                                <a href="{{ route('transactionremittance.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                    </svg>
                                    Transaction
                                </a>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                        Renter Remittance</span>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                        {{ $branch->branchname }}</span>
                                </div>
                            </li>
                            
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                        {{ $renter->lastname }}, {{ $renter->firstname }}</span>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                        {{ $cabinet->cabinetname }}</span>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                        Payment Record</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <!-- Error & Success Notification -->
                    @include('layouts.notifications') 
                    <!-- Modal content -->
                    <div class="p-4 relative bg-white rounded-lg dark:bg-gray-800">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Renter Remittance Information
                            </h3>
                        </div>
                        <!-- Modal body -->
                        <div class="grid gap-4 mb-4 grid-cols-2" x-data="{ rent: '{{ $cabinet->cabinetprice }}', rentpay: 0,  bal: 0}" x-effect="bal = rent - rentpay">
                            <!-- fullname -->
                            <div class="col-span-2 sm:col-span-1">
                                <div class="form-group mt-4">
                                    <x-input-label for="fullname" :value="__('Full Name')" />
                                    <x-text-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" value="{{ $renter->lastname.', '.$renter->firstname }}" required autofocus readonly/>
                                    <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
                                </div>
                            </div>
                            <!-- cabinet -->
                            <div class="col-span-2 sm:col-span-1 ">
                                <div class="form-group mt-4">
                                    <x-input-label for="cabinetname" :value="__('Cabinet No.')" />
                                    <x-text-input id="cabinetname" class="block mt-1 w-full" type="text" name="cabinetname" :value="old('cabinetname', $cabinet->cabinetname)" required autofocus autocomplete="off" readonly/>
                                    <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                </div>
                            </div>

                            <!-- start week -->
                            <div class="col-span-2 sm:col-span-1 ">
                                <div class="form-group mt-4">
                                    <x-input-label for="sweek" :value="__('Start Week')" />
                                    <x-text-input id="sweek" class="block mt-1 w-full" type="text" name="sweek" :value="old('sweek', $lwstartweek)" required autofocus autocomplete="off" readonly/>
                                    <x-input-error :messages="$errors->get('sweek')" class="mt-2" />
                                </div>
                            </div>

                            <!-- end week -->
                            <div class="col-span-2 sm:col-span-1 ">
                                <div class="form-group mt-4">
                                    <x-input-label for="eweek" :value="__('End Week')" />
                                    <x-text-input id="eweek" class="block mt-1 w-full" type="text" name="eweek" :value="old('eweek', $lwendweek)" required autofocus autocomplete="off" readonly/>
                                    <x-input-error :messages="$errors->get('eweek')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Total Sales -->
                            <div class="col-span-2 sm:col-span-1 ">
                                <div class="form-group mt-4">
                                    <x-input-label for="totalsales" :value="__('Total Sales')" />
                                    <x-text-input id="totalsales" class="block mt-1 w-full" type="text" name="totalsales" :value="old('totalsales', $cabinet->cabinetname)" required autofocus autocomplete="off" readonly/>
                                    <x-input-error :messages="$errors->get('totalsales')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Total Remit -->
                            <div class="col-span-2 sm:col-span-1 ">
                                <div class="form-group mt-4">
                                    <x-input-label for="totalremit" :value="__('Total Remit')" />
                                    <x-text-input id="totalremit" class="block mt-1 w-full" type="text" name="totalremit" :value="old('totalremit', $cabinet->cabinetname)" required autofocus autocomplete="off" readonly/>
                                    <x-input-error :messages="$errors->get('totalremit')" class="mt-2" />
                                </div>
                            </div>

                            <!-- avatar -->
                            <div class="col-span-2 sm:col-span-1">
                                <div class="form-group mt-4">
                                    <x-input-label for="imageproof" value="Upload Image Proof" />
                                    <x-text-input id="imageproof" name="imageproof" type="file"  class="mt-1 block w-full mt-1" :value="old('imageproof')" autofocus autocomplete="off" required/>
                                    <x-input-error class="mt-2" :messages="$errors->get('imageproof')" />
                                </div>
                            </div>
                            
                            
                        </div>
                        <!-- Button -->
                        <div class="flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button type="submit" class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 0 1 1-1h11.586a1 1 0 0 1 .707.293l2.414 2.414a1 1 0 0 1 .293.707V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5Z"/>
                                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 4h8v4H8V4Zm7 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                Next
                            </button>
                            <a href="{{ route('transactionremittance.renterremittancerecords',[$branch->branchid,$renter->rentersid,$cabinet->cabid]) }}" class="py-2 px-3 ms-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>