<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('sales.index') }}"> Sales</a> / <u>{{ __('Sell Item') }}</u>
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <form action="{{ route('sales.store') }}" enctype="multipart/form-data" method="POST" class="p-4 md:p-5">
                        @csrf
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
                                            Sell Product 
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- avatar -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="name" value="Upload Product Image" />
                                                <x-text-input id="salesavatar" name="salesavatar" type="file"  class="mt-1 block w-full mt-1" :value="old('salesavatar')" autofocus autocomplete="off" required/>
                                                <x-input-error class="mt-2" :messages="$errors->get('salesavatar')" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- branchname -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="branchname" :value="__('Branch Name')" />
                                                <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" value="{{ Auth()->user()->branchname; }}" required autofocus autocomplete="off" readonly/> 
                                                
                                                <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1 ">
                                            <!-- cabname -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="cabinetname" :value="__('Cabinet No.')" />
                                                <x-text-input id="cabinetname" class="block mt-1 w-full" type="text" name="cabinetname" :value="old('cabinetname')" required autofocus autocomplete="off" />
                                                <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- productname -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="productname" :value="__('Product Name')" />
                                                <x-text-input id="productname" class="block mt-1 w-full" type="text" name="productname" :value="old('productname')" required autofocus autocomplete="off" />
                                                <x-input-error :messages="$errors->get('productname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- qty -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="qty" :value="__('QTY')" />
                                                <x-text-input id="qty" class="block mt-1 w-full" type="text" name="qty" :value="old('qty')" required autofocus autocomplete="off" />
                                                <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                                            </div>
                                        </div>
                                
                                        <div class="col-span-2 sm:col-span-1">
                                                <!-- srp -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="srp" :value="__('Price')" />
                                                <x-text-input id="srp" class="block mt-1 w-full" type="text" name="srp" :value="old('srp')" required autofocus autocomplete="off" />
                                                <x-input-error :messages="$errors->get('srp')" class="mt-2" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-span-2 sm:col-span-1">
                                                <!-- total -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="total" :value="__('Total Amount')" />
                                                <x-text-input id="total" class="block mt-1 w-full" type="text" name="total" :value="old('total')" required autofocus autocomplete="off" />
                                                <x-input-error :messages="$errors->get('total')" class="mt-2" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-span-2 sm:col-span-1 ">
                                            <!-- rpnotes -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="snotes" :value="__('Remarks')" />
                                                <x-text-input id="snotes" class="block mt-1 w-full" type="text" name="snotes" :value="old('snotes')" required autofocus autocomplete="off" />
                                                <x-input-error :messages="$errors->get('snotes')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between col-span-2 sm:col-span-1">
                                            <x-primary-button class="ms-4">
                                                <a class="btn btn-primary" > Save</a>
                                            </x-primary-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
   
