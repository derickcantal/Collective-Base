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
                        <form action="{{ route('transactionrequest.create') }}" method="POST" class="p-4 md:p-5">
                            @csrf   
                            @method('patch')
                            <!-- Error & Success Notification -->
                            @include('layouts.notifications') 
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        Renters Requests Information
                                    </h3>
                                </div>
                                <!-- Modal body -->
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- branchname -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="branchname" :value="__('Branch Name')" />
                                                <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" :value="old('branchname', $branchname)" required autofocus autocomplete="off" readonly/> 
                                                
                                                <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <!-- cabinetnumber -->
                                            <div class="form-group mt-4">
                                                <x-input-label for="cabinetname" :value="__('Cabinet No.')" />
                                                <!-- <x-text-input id="cabinetname" class="block mt-1 w-full" type="text" name="cabinetname" :value="old('cabinetname')" required autofocus autocomplete="off" /> -->
                                                <select id="cabinetname" name="cabinetname" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('cabinetname')">
                                                    @foreach($cabinet as $cabinets)    
                                                        <option value = "{{ $cabinets->cabinetname}}">{{ $cabinets->cabinetname}}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('cabinetname')" class="mt-2" />
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between col-span-2 sm:col-span-2">
                                            <x-primary-button class="ms-4">
                                                <a class="btn btn-primary" > Select</a>
                                            </x-primary-button>
                                            </div>
                                        </div>
                                        
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