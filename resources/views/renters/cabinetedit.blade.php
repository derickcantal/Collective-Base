<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('renters.index') }}"> Renters</a></u> / {{ __('Modify Cabinet') }} / {{ $cabinet->branchname }}
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <form action="{{ route('renters.updatecabinet',$cabinet->cabid) }}" method="POST" class="p-4 md:p-5">
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
                                            Cabinet Information
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2 sm:col-span-1 ">
                                            <!-- nn -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="cabinetname" :value="__('Cabinet Name')" />
                                                <x-text-input id="cabinetname" class="block mt-1 w-full" type="text" name="cabinetname" :value="old('cabinetname',$cabinet->cabinetname)" required autofocus autocomplete="off" readonly/>
                                                <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1 ">
                                            <!-- nn -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="cabinetprice" :value="__('Rent Price')" />
                                                <x-text-input id="cabinetprice" class="block mt-1 w-full" type="number" name="cabinetprice" :value="old('cabinetprice',$cabinet->cabinetprice)" required autofocus autocomplete="off" />
                                                <x-input-error :messages="$errors->get('cabinetprice')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- branchname -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="renter" :value="__('Renter Name')" />
                                                <!-- <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" :value="old('branchname')" required autofocus autocomplete="off" /> -->
                                                <select id="renter" name="renter" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('branchname')">
                                                <option value = "Vacant">Vacant</option>
                                                    @foreach($rent as $renter)    
                                                        <option value = "{{ $renter->userid}}">{{ $renter->lastname }}, {{ $renter->firstname }} - {{ $renter->email }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('renter')" class="mt-2" />
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
   
