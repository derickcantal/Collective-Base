<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.transaction.navigation')
        </div>
    </div>
<div class="py-8">
	<div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
			<div class="py-8">
				<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
					<div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                        <form action="{{ route('transactionsales.update',$sales->salesid) }}" enctype="multipart/form-data" method="POST" class="p-4 md:p-5">
                            @csrf
                            @method('PATCH')   
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                <!-- Breadcrumb -->
                                <nav class="flex px-5 py-3 text-gray-700  bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                        <li class="inline-flex items-center">
                                        <a href="{{ route('transactionsales.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                            </svg>
                                            Sales
                                        </a>
                                        </li>
                                        <li>
                                        <div class="flex items-center">
                                            <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                            <a href="" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Modify</a>
                                        </div>
                                        </li>
                                        <li aria-current="page">
                                        <div class="flex items-center">
                                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ $sales->salesid }}</span>
                                        </div>
                                        </li>
                                    </ol>
                                </nav>
                                <!-- Error & Success Notification -->
                                @include('layouts.notifications') 
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        Modify Sales
                                    </h3>
                                </div>
                                
                                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                    <div class="max-w-xl">
                                        <div>
                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Product Image') }}
                                        </h2>

                                        <img width="100" height="100" class="rounded-full mt-4" src="{{ asset("/storage/$sales->salesavatar") }}" alt="proof avatar" />

                                            <x-input-label for="name" value="Upload Receipt" />
                                            <x-text-input id="salesavatar" name="salesavatar" type="file"  class="mt-1 block w-full mt-3" :value="old('salesavatar')" autofocus autocomplete="off" />
                                            <x-input-error class="mt-2" :messages="$errors->get('salesavatar')" />
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal body -->
                                <div class="grid gap-4 mb-4 grid-cols-2" x-data="{ srp: {{ $sales->srp }} , qty: {{ $sales->qty }},  total: 0 }" x-effect="total = qty * srp">
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- branchname -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="branchname" :value="__('Branch Name')" />
                                            <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" value="{{ $sales->branchname; }}" required autofocus autocomplete="off" readonly/> 
                                            
                                            <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1 ">
                                        <!-- cabname -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="cabinetname" :value="__('Cabinet No.')" />
                                            <x-text-input id="cabinetname" class="block mt-1 w-full" type="text" name="cabinetname" :value="old('cabinetname', $sales->cabinetname)" required autofocus autocomplete="off" readonly />
                                            
                                            <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- productname -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="productname" :value="__('Product Name')" />
                                            <x-text-input id="productname" class="block mt-1 w-full" type="text" name="productname" :value="old('productname', $sales->productname)" required autofocus autocomplete="off" />
                                            <x-input-error :messages="$errors->get('productname')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                            <!-- srp -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="srp" :value="__('Price')" />
                                            <x-text-input id="srp" x-model.number="srp " class="block mt-1 w-full" type="number" name="srp" :value="old('srp', $sales->srp)" required autofocus autocomplete="off" />
                                            <x-input-error :messages="$errors->get('srp')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- qty -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="qty" :value="__('QTY')" />
                                            <x-text-input id="qty" x-model.number="qty" class="block mt-1 w-full" type="number" name="qty" :value="old('qty', $sales->qty)" required autofocus autocomplete="off" />
                                            <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                            <!-- total -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="total" :value="__('Total Amount')" />
                                            <x-text-input id="total" x-model.number="total"  class="block mt-1 w-full" type="text" name="total" :value="old('total')" required autofocus autocomplete="off" readonly/>
                                            <x-input-error :messages="$errors->get('total')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- avatar -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="name" value="Upload Payment Proof" />
                                            <x-text-input id="payavatar" name="payavatar" type="file"  class="mt-1 block w-full mt-1" :value="old('payavatar')" autofocus autocomplete="off"/>
                                            <x-input-error class="mt-2" :messages="$errors->get('payavatar')" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- productname -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="paytype" :value="__('Payment Mode')" />
                                            <!-- <x-text-input id="paytype" class="block mt-1 w-full" type="text" name="paytype" :value="old('paytype')" required autofocus autocomplete="off" /> -->
                                            <select id="paytype" name="paytype" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('paytype')" required>
                                                <option value = "Cash">Cash</option>
                                                <option value = "Mobile Bank Transfer">Mobile Bank Transfer</option>
                                                <option value = "Online Wallet (GCash/Maya)">Online Wallet (GCash/Maya)</option>
                                                <option value = "Credit Card">Credit Card</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('paytype')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- productname -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="payref" :value="__('Payment Reference No.')" />
                                            <x-text-input id="payref" class="block mt-1 w-full" type="text" name="payref" :value="old('payref',$sales->payref)" autofocus autocomplete="off" />
                                            <x-input-error :messages="$errors->get('payref')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1 ">
                                        <!-- cabname -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="returned" :value="__('Returned?')" />
                                            <select id="returned" name="returned" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('returned')" required>
                                                    <option value = "N">No</option>
                                                    <option value = "Y">Yes</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('returned')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1 ">
                                        <!-- rpnotes -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="snotes" :value="__('Remarks')" />
                                            <x-text-input id="snotes" class="block mt-1 w-full" type="text" name="snotes" :value="old('snotes', $sales->snotes)" required autofocus autocomplete="off" />
                                            <x-input-error :messages="$errors->get('snotes')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1 ">
                                        <!-- cabname -->
                                        <div class="form-group mt-4">
                                            <x-text-input id="cabid" class="block mt-1 w-full" type="hidden" name="cabid" :value="old('cabid', $sales->cabid)" required autofocus autocomplete="off" readonly />
                                            
                                            <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                    <button type="submit" class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 0 1 1-1h11.586a1 1 0 0 1 .707.293l2.414 2.414a1 1 0 0 1 .293.707V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5Z"/>
                                            <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 4h8v4H8V4Zm7 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                        Update
                                    </button>
                                    <a href="{{ route('transactionsales.index') }}" class="py-2 px-3 ms-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
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
        </div>
    </div>
</div>
</x-app-layout>