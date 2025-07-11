<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.transaction.navigation')
        </div>
    </div>
	<div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <form action="{{ route('transactionremittance.store', $cabinet->cabid) }}" enctype="multipart/form-data" method="POST">
                    @csrf   
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
                                        Rental Payments</span>
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
                                    Renter Payment Information
                            </h3>
                        </div>
                        <!-- Modal body -->
                        <div class="grid gap-4 mb-4 grid-cols-2" x-data="{ rent: '{{ $totalbalance }}', rentpay: 0,  bal: 0}" x-effect="bal = rent - rentpay">
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
                            <div class="col-span-2 sm:col-span-1">
                                <div class="form-group mt-4">
                                    <x-input-label for="rpmonth" :value="__('Applicable Month')" />
                                    <div class="flex flex-col items-stretch justify-start flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3  mt-2">
                                        <x-text-input id="rpmonth" class="block mt-1 w-full" type="text" name="rpmonth" :value="old('rpmonth', $rpmonth)" required autofocus autocomplete="off" readonly/>
                                        <x-input-error :messages="$errors->get('rpmonth')" class="mt-2" />
                                        <x-text-input id="rpyear" class="block mt-1 w-full" type="text" name="rpyear" :value="old('rpmonth', $rpyear)" required autofocus autocomplete="off" readonly/>
                                        <x-input-error :messages="$errors->get('rpyear')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                            <!-- avatar -->
                            <div class="col-span-2 sm:col-span-1">
                                <div class="form-group mt-4">
                                    <x-input-label for="payavatar" value="Payment Proof" />
                                    <x-text-input id="payavatar" name="payavatar" type="file"  class="mt-1 block w-full mt-1" :value="old('payavatar')" autofocus autocomplete="off"/>
                                    <x-input-error class="mt-2" :messages="$errors->get('payavatar')" />
                                </div>
                            </div>
                            <!-- rpamount -->
                            <div class="col-span-2 sm:col-span-1">
                                <div class="form-group mt-4">
                                    <x-input-label for="rpamount" :value="__('Rental Amount')" />
                                    <x-text-input id="rpamount" x-model.number="rent" class="block mt-1 w-full" type="number" name="rpamount"  required autofocus autocomplete="off" readonly/>
                                    <x-input-error :messages="$errors->get('rpamount')" class="mt-2" />
                                </div>
                            </div>
                            <!-- rppaytype -->
                            <div class="col-span-2 sm:col-span-1">
                                <div class="form-group mt-4">
                                    <x-input-label for="rppaytype" :value="__('Payment Mode')" />
                                    <select id="rppaytype" name="rppaytype" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('rppaytype')" >
                                        <option value = "Cash">Cash</option>
                                        <option value = "Bank Transfer">Bank Transfer</option>
                                    </select>
                                    
                                    <x-input-error :messages="$errors->get('rppaytype')" class="mt-2" />
                                </div>
                            </div>
                            <!-- paidamount -->
                            <div class="col-span-2 sm:col-span-1 ">
                                <div class="form-group mt-4">
                                    <x-input-label for="paidamount" :value="__('Amount to be Paid')" />
                                    <x-text-input id="paidamount" x-model.number="rentpay" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="number" name="paidamount" required autofocus autocomplete="off"/>
                                    <x-input-error :messages="$errors->get('paidamount')" class="mt-2" />
                                </div>
                            </div>
                            <!-- totalbalance -->
                            <div class="col-span-2 sm:col-span-1 ">
                                <div class="form-group mt-4">
                                    <x-input-label for="totalbalance" :value="__('Total Balance')" />
                                    <x-text-input id="totalbalance" x-model.number="bal" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="number" name="totalbalance" required autofocus autocomplete="off" readonly/>
                                    <x-input-error :messages="$errors->get('totalbalance')" class="mt-2" />
                                </div>
                            </div>
                            <!-- Notes -->
                            <div class="col-span-2 sm:col-span-1 ">
                                <div class="form-group mt-4">
                                    <x-input-label for="rpnotes" :value="__('Notes')" />
                                    <x-text-input id="rpnotes" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" name="rpnotes" value="" autofocus autocomplete="off"/>
                                    <x-input-error :messages="$errors->get('rpnotes')" class="mt-2" />
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
                                Save
                            </button>
                            <a href="{{ route('transactionremittance.rentalpaymentrecords',[$branch->branchid,$renter->rentersid,$cabinet->cabid]) }}" class="py-2 px-3 ms-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
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