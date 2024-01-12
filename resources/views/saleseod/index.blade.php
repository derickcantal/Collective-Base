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
                                <div>
                                    @if ($message = Session::get('success'))
                                    <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
                                        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                        </svg>
                                        <span class="sr-only">Info</span>
                                        <div>
                                            <span class="font-medium">Success!</span> {{ $message }}
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if ($message = Session::get('failed'))
                                    <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
                                        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                        </svg>
                                        <span class="sr-only">Info</span>
                                        <div>
                                            <span class="font-medium">Failed!</span> {{ $message }}
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            EOD Information
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                    <form action="{{ route('saleseod.store') }}" method="POST" class="p-4 md:p-5">
                                    @csrf 
                                        <div class="grid gap-4 mb-4 grid-cols-2" x-data="{ totalsales: {{ $totalsales }}, rentalpay: {{ $totalrentpay }},  requests: {{ $totalrequests }}, expenses: 0, totalcash: 0}" x-effect="totalcash = (totalsales + rentalpay) - (requests + expenses)">
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- branchname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="branchname" :value="__('Branch Name')" />
                                                    <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" value="{{ Auth()->user()->branchname; }}" required autofocus autocomplete="off" readonly/> 
                                                    
                                                    <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- productname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="totalsales" :value="__('TOTAL SALES')" />
                                                    <x-text-input id="totalsales" x-model.number="totalsales" class="block mt-1 w-full" type="number" name="totalsales" value="{{ $totalsales }}" required autofocus autocomplete="off" readonly />
                                                    <x-input-error :messages="$errors->get('totalsales')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- productname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="rentalpayments" :value="__('TOTAL RENTAL PAYMENTS')" />
                                                    <x-text-input id="rentalpayments" x-model.number="rentalpay" class="block mt-1 w-full" type="number" name="rentalpayments" value="{{ $totalsales }}" required autofocus autocomplete="off" readonly />
                                                    <x-input-error :messages="$errors->get('rentalpayments')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- productname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="requestpayments" :value="__('TOTAL REQUESTS PAYMENTS')" />
                                                    <x-text-input id="requestpayments" x-model.number="requests" class="block mt-1 w-full" type="number" name="requestpayments" value="{{ $totalsales }}" required autofocus autocomplete="off" readonly />
                                                    <x-input-error :messages="$errors->get('requestpayments')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- productname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="otherexpenses" :value="__('EXPENSES')" />
                                                    <x-text-input id="otherexpenses" x-model.number="expenses" class="block mt-1 w-full" type="number" name="otherexpenses" value="{{ $totalsales }}" required autofocus autocomplete="off"  />
                                                    <x-input-error :messages="$errors->get('otherexpenses')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- productname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="totalcash" :value="__('TOTAL CASH')" />
                                                    <x-text-input id="totalcash" x-model.number="totalcash" class="block mt-1 w-full" type="number" name="totalcash" value="{{ $totalsales }}" required autofocus autocomplete="off"  readonly/>
                                                    <x-input-error :messages="$errors->get('totalcash')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- rpnotes -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="notes" :value="__('Remarks')" />
                                                    <x-text-input id="notes" class="block mt-1 w-full" type="text" name="notes" :value="old('snotes')" autofocus autocomplete="off" />
                                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-span-2 sm:col-span-1 ">
                                                <div class="flex items-center justify-between col-span-2 sm:col-span-1">
                                                    <x-danger-button class="ms-4">
                                                        <a class="btn btn-primary" > Save</a>
                                                    </x-danger-button>
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


    
