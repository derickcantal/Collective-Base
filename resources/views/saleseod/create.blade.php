  
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('saleseod.index') }}"> EOD</a> /<u> {{ __('End of Day Sales') }}</u>
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <div class="relative p-4 w-full max-w-full max-h-full">
                                <!-- Error & Success Notification -->        
                                <div>
                                    @if ($errors->any())
                                    <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <span class="sr-only">Danger</span>
                                    <div>
                                        <span class="font-medium">Ensure that these requirements are met:</span>
                                        <ul class="mt-1.5 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

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
                                </div>
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            End of Day Sales Information
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                    <form action="{{ route('saleseod.store') }}" enctype="multipart/form-data" method="POST" class="p-4 md:p-5">
                                    @csrf 
                                    @method('put')
                                        <div class="grid gap-4 mb-4 grid-cols-2" x-data="{ totalitem: {{ $totalitem }},totalsales: {{ $totalsales }}, rentalpay: {{ $totalrentpay }},  requests: {{ $totalrequests }}, expenses: 0, totalcash: 0}" x-effect="totalcash = (totalsales + rentalpay) - (requests + expenses)">
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
                                                    <x-input-label for="totalitem" :value="__('TOTAL ITEM SOLD')" />
                                                    <x-text-input id="totalitem" x-model.number="totalitem" class="block mt-1 w-full" type="number" name="totalitem" value="{{ $totalitem }}" required autofocus autocomplete="off" readonly />
                                                    <x-input-error :messages="$errors->get('totalitem')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- productname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="totalsales" :value="__('TOTAL SALES')" />
                                                    <x-text-input id="totalsales" x-model.number="totalsales" class="block mt-1 w-full" type="number" name="totalsales" value="{{ number_format($totalsales,2) }}" required autofocus autocomplete="off" readonly />
                                                    <x-input-error :messages="$errors->get('totalsales')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- productname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="rentalpayments" :value="__('TOTAL RENTAL PAYMENTS')" />
                                                    <x-text-input id="rentalpayments" x-model.number="rentalpay" class="block mt-1 w-full" type="number" name="rentalpayments" value="{{ $totalrentpay }}" required autofocus autocomplete="off" readonly />
                                                    <x-input-error :messages="$errors->get('rentalpayments')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- productname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="requestpayments" :value="__('TOTAL REQUESTS PAYMENTS')" />
                                                    <x-text-input id="requestpayments" x-model.number="requests" class="block mt-1 w-full" type="number" name="requestpayments" value="{{ $totalrequests }}" required autofocus autocomplete="off" readonly />
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
                                            <div class="flex items-center justify-between col-span-2 sm:col-span-1">
                                                <x-primary-button class="ms-4">
                                                    <a class="btn btn-primary" > Save</a>
                                                </x-primary-button>
                                            </div>
                                        </div>
                                    </form>
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