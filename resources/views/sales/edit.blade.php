<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('sales.index') }}"> Sales</a></u> / {{ __('Modify Sales') }} / {{ $sales->salesid }}
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <form action="{{ route('sales.update',$sales->salesid) }}" enctype="multipart/form-data" method="POST" class="p-4 md:p-5">
                        @csrf
                        @method('PUT')   
                            <div class="relative p-4 w-full max-w-full max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
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
                                                <x-text-input id="salesavatar" name="salesavatar" type="file"  class="mt-1 block w-full mt-3" :value="old('salesavatar', $sales->salesavatar)" autofocus autocomplete="off" required/>
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
                                                <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" value="{{ Auth()->user()->branchname; }}" required autofocus autocomplete="off" readonly/> 
                                                
                                                <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1 ">
                                            <!-- cabname -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="cabinetname" :value="__('Cabinet No.')" />
                                                <x-text-input id="cabinetname" class="block mt-1 w-full" type="text" name="cabinetname" :value="old('cabinetname', $sales->cabinetname)" required autofocus autocomplete="off" />
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
                                        <div class="col-span-2 sm:col-span-1 ">
                                            <!-- rpnotes -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="snotes" :value="__('Remarks')" />
                                                <x-text-input id="snotes" class="block mt-1 w-full" type="text" name="snotes" :value="old('snotes', $sales->snotes)" required autofocus autocomplete="off" />
                                                <x-input-error :messages="$errors->get('snotes')" class="mt-2" />
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="flex items-center justify-between col-span-2 sm:col-span-1">
                                        <x-primary-button class="ms-4">
                                            <a class="btn btn-primary" > Update</a>
                                        </x-primary-button>
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
   
