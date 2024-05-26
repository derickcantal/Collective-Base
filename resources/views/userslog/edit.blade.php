<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('eod.index') }}"> Sales EOD</a></u> / {{ __('Modify EOD') }} / {{ $seod->branchname }}
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <form action="{{ route('eod.update',$seod->seodid) }}" method="POST" class="p-4 md:p-5">
                        @csrf
                        @method('PUT')   
                            <div class="relative p-4 w-full max-w-full max-h-full">
                                <!-- Error & Success Notification -->
                                @include('layouts.notifications') 
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Sales EOD Information
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                    <img width="100" height="100" class="rounded-full mt-4" src="{{ asset("/storage/$seod->avatar") }}" alt="user avatar" />
                                        <div class="grid gap-4 mb-4 grid-cols-2">
                                            <div class="col-span-2 sm:col-span-1 ">
                                                <!-- username -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="username" :value="__('Username')" />
                                                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $seod->username)" required autofocus />
                                                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- Email Address -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="email" :value="__('Email')" />
                                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $seod->email)" required />
                                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- Password -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="password" :value="__('Password')" />

                                                    <x-text-input id="password" class="block mt-1 w-full"
                                                                    type="password"
                                                                    name="password"
                                                                     />

                                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- Confirm Password -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                                                    type="password"
                                                                    name="password_confirmation"  />

                                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                                </div>                    
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- firstname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="firstname" :value="__('First Name')" />
                                                    <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname', $seod->firstname)" required autofocus/>
                                                    <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- middlename -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="middlename" :value="__('Middle Name')" />
                                                    <x-text-input id="middlename" class="block mt-1 w-full" type="text" name="middlename" :value="old('middlename', $seod->middlename)" required autofocus />
                                                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                    <!-- lastname -->
                                                    <div class="form-group mt-4">
                                                    <x-input-label for="lastname" :value="__('Last Name')" />
                                                    <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname', $seod->lastname)" required />
                                                    <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- birthdate -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="birthdate" :value="__('Birth Date')" />
                                                    <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="date('Y-m-d',strtotime(old('birthdate', $seod->birthdate)))" required autofocus autocomplete="bday" />
                                                    <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- branchname -->
                                                @php
                                                    $op1_b = '';
                                                    $op2_b = '';
                                                    $op3_b = '';
                                                    $op4_b = '';
                                                    $op5_b = '';
                                                    $op6_b = '';
                                                    if ($seod->branchname == 'CB Main'):
                                                        $op1_b = 'selected = "selected"';
                                                    elseif ($seod->branchname == 'CB Annex'):
                                                        $op2_b = 'selected = "selected"';
                                                    elseif ($seod->branchname == 'CB Complex'):
                                                        $op3_b = 'selected = "selected"';
                                                    elseif ($seod->branchname == 'CB Plus 1'):
                                                        $op4_b = 'selected = "selected"';
                                                    elseif ($seod->branchname == 'CB Plus 2'):
                                                        $op5_b = 'selected = "selected"';  
                                                    elseif ($seod->branchname == 'CB Plus 3'):
                                                        $op6_b = 'selected = "selected"'; 
                                                    endif;
                                                @endphp
                                                <div class="form-group mt-4">
                                                    <x-input-label for="branchname" :value="__('Branch Name')" />
                                                    <!-- <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" :value="old('branchname')" required autofocus autocomplete="off" /> -->
                                                    <select id="branchname" name="branchname" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('branchname', $seod->branchname))">
                                                        <option value = "CB Main" {{ $op1_b; }}">CB Main</option>
                                                        <option value = "CB Annex" {{ $op2_b; }}">CB Annex</option>
                                                        <option value = "CB Complex" {{ $op3_b; }}}">CB Complex</option>
                                                        <option value = "CB Plus 1" {{ $op4_b; }}">CB Plus 1</option>
                                                        <option value = "CB Plus 2" {{ $op5_b; }}">CB Plus 2</option>
                                                        <option value = "CB Plus 3" {{ $op6_b; }}">CB Plus 3</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- accesstype -->
                                                @php
                                                    $op1_a = '';
                                                    $op2_a = '';
                                                    $op3_a = '';
                                                    if ($seod->accesstype == 'Administrator'):
                                                        $op1_a = 'selected = "selected"';
                                                    elseif ($seod->accesstype == 'Supervisor'):
                                                        $op2_a = 'selected = "selected"';
                                                    elseif ($seod->accesstype == 'Cashier'):
                                                        $op3_a = 'selected = "selected"';
                                                    
                                                    endif;
                                                    
                                                @endphp
                                                <div class="form-group mt-4">
                                                    <x-input-label for="accesstype" :value="__('Access Type')" />
                                                    <!-- <x-text-input id="accesstype" class="block mt-1 w-full" type="text" name="accesstype" :value="old('accesstype')" required autofocus autocomplete="off" /> -->
                                                    <select id="accesstype" name="accesstype" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('accesstype', $seod->accesstype)">
                                                        <option value ="Administrator" {{ $op1_a; }}">Administrator</option>
                                                        <option value ="Supervisor" {{ $op2_a; }}">Supervisor</option>
                                                        <option value ="Cashier" {{ $op3_a; }}">Cashier</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('accesstype')" class="mt-2" />
                                                    
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- status -->
                                                @php
                                                
                                                    $op1 = '';
                                                    $op2 = '';
                                                    if ($seod->status == 'Active'):
                                                        $op1 = 'selected = "selected"';
                                                    elseif ($seod->status == 'Inactive'):
                                                        $op2 = 'selected = "selected"';
                                                    endif;
                                                @endphp
                                                <div class="form-group mt-4">
                                                    <x-input-label for="status" :value="__('Status')" />
                                                    <!-- <x-text-input id="status" class="block mt-1 w-full" type="text" name="status" :value="old('status')" required autofocus autocomplete="off" /> -->
                                                    <select id="status" name="status" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('status', $seod->status)">
                                                        <option value ="Active"  {{ $op1; }}>Active</option>
                                                        <option value ="Inactive"  {{ $op2; }}">Inactive</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
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
   
