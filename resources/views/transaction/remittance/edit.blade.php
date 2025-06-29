<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.transaction.navigation')
        </div>
    </div>
	<div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <form action="{{ route('transactionremittance.update',$rentalPayments->rpid) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PATCH')   
                    <!-- Error & Success Notification -->
                    @include('layouts.notifications') 
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
                                    <x-text-input id="avatarproof" name="avatarproof" type="file"  class="mt-1 block w-full mt-3" :value="old('avatarproof', $rentalPayments->avatarproof)" autofocus autocomplete="off" required/>
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
                                        <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" :value="old('branchname', $rentalPayments->branchname)" required autofocus autocomplete="off" readonly/> 
                                        <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1 ">
                                    <!-- cabname -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="cabinetname" :value="__('Cabinet No.')" />
                                        <x-text-input id="cabinetname" class="block mt-1 w-full" type="text" name="cabinetname" :value="old('cabinetname', $rentalPayments->cabinetname)" required autofocus autocomplete="off" readonly/>
                                        <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- username -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="username" :value="__('User Name')" />
                                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $rentalPayments->username)" required autofocus autocomplete="off" readonly/>
                                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <!-- firstname -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="firstname" :value="__('First Name')" />
                                        <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname', $rentalPayments->firstname)" required autofocus autocomplete="given-name" readonly/>
                                        <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                    </div>
                                </div>
                        
                                <div class="col-span-2 sm:col-span-1">
                                        <!-- lastname -->
                                        <div class="form-group mt-4">
                                        <x-input-label for="lastname" :value="__('Last Name')" />
                                        <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname', $rentalPayments->lastname)" required autofocus autocomplete="family-name" readonly/>
                                        <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                        <!-- rpmonth -->
                                        <div class="form-group mt-4">
                                        <x-input-label for="rpmonth" :value="__('Applicable Month (Month - Year)')" />
                                        <x-text-input id="rpmonth" class=" mt-1" type="text" name="rpmonth" :value="old('rpmonth', $rentalPayments->rpmonth)" required autofocus autocomplete="off" readonly/> -
                                        <x-text-input id="rpyear" class=" mt-1" type="text" name="rpyear" :value="old('rpmonth', $rentalPayments->rpyear)" required autofocus autocomplete="off" readonly/>
                                        <x-input-error :messages="$errors->get('rpyear')" class="mt-2" />
                                        <x-input-error :messages="$errors->get('rpmonth')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                        <!-- rpamount -->
                                        <div class="form-group mt-4">
                                        <x-input-label for="rpamount" :value="__('Rental Amount')" />
                                        <x-text-input id="rpamount" class="block mt-1 w-full" type="text" name="rpamount" :value="old('rpamount', $rentalPayments->rpamount)" required autofocus autocomplete="off" />
                                        <x-input-error :messages="$errors->get('rpamount')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                        <!-- rppaytype -->
                                        @php
                                        $op1_c = '';
                                        $op2_c = '';
                                        if ($rentalPayments->rppaytype == 'Cash'):
                                            $op1_c = 'selected = "selected"';
                                        elseif ($rentalPayments->rppaytype == 'Bank Transfer'):
                                            $op2_c = 'selected = "selected"';
                                        
                                        endif;
                                    @endphp
                                        <div class="form-group mt-4">
                                        <x-input-label for="rppaytype" :value="__('Payment Mode')" />
                                        <select id="rppaytype" name="rppaytype" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('rppaytype', $rentalPayments->rppaytype))" >
                                            <option value = "Cash" {{ $op1_c; }}">Cash</option>
                                            <option value = "Bank Transfer" {{ $op2_c; }}">Bank Transfer</option>
                                        </select>
                                        
                                        <x-input-error :messages="$errors->get('rppaytype')" class="mt-2" />
                                    </div>
                                </div>
                                
                                
                                <div class="col-span-2 sm:col-span-1 ">
                                    <!-- rpnotes -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="rpnotes" :value="__('Notes')" />
                                        <x-text-input id="rpnotes" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="textarea" name="rpnotes" :value="old('rpnotes', $rentalPayments->rpnotes)" required autofocus autocomplete="off" />
                                        <x-input-error :messages="$errors->get('rpnotes')" class="mt-2" />
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
                </form>
            </div>
        </div>
    </div>
</x-app-layout>