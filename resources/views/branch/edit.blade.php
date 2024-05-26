<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('branch.index') }}"> Branch</a></u> / {{ __('Modify Branch') }} / {{ $branch->branchname }}
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <form action="{{ route('branch.update',$branch->branchid) }}" method="POST" class="p-4 md:p-5">
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
                                            Branch Information
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                            <div class="col-span-2 sm:col-span-1 ">
                                                <!-- nn -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="branchname" :value="__('Branch Name')" />
                                                    <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" :value="old('branchname',$branch->branchname)" required autofocus autocomplete="off" />
                                                    <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- nn -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="branchaddress" :value="__('Address')" />
                                                    <x-text-input id="branchaddress" class="block mt-1 w-full" type="text" name="branchaddress" :value="old('branchaddress',$branch->branchaddress)" required autofocus autocomplete="off" />
                                                    <x-input-error :messages="$errors->get('branchaddress')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- nn -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="branchcontact" :value="__('Contact No.')" />
                                                    <x-text-input id="branchcontact" class="block mt-1 w-full" type="text" name="branchcontact" :value="old('branchcontact',$branch->branchcontact)" required autofocus autocomplete="off" />
                                                    <x-input-error :messages="$errors->get('branchcontact')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- nn -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="branchemail" :value="__('Email')" />
                                                    <x-text-input id="branchemail" class="block mt-1 w-full" type="email" name="branchemail" :value="old('branchemail',$branch->branchemail)" required autocomplete="email" />
                                                    <x-input-error :messages="$errors->get('branchemail')" class="mt-2" />
                                                </div>
                                            </div>

                                            <div class="col-span-2 sm:col-span-1">
                                                    <!-- nn -->
                                                    <div class="form-group mt-4">
                                                    <x-input-label for="cabinetcount" :value="__('Cabinet Count')" />
                                                    <x-text-input id="cabinetcount" class="block mt-1 w-full" type="number" name="cabinetcount" :value="old('cabinetcount',$branch->cabinetcount)" required autofocus autocomplete="off" />
                                                    <x-input-error :messages="$errors->get('cabinetcount')" class="mt-2" />
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
   
