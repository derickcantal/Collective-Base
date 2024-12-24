<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.transaction.navigation')
        </div>
    </div>
<div class="py-8">
	<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
			<div class="py-8">
				<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
					<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form action="{{ route('attendance.update',$attendance->attid) }}" enctype="multipart/form-data" method="POST" class="p-4 md:p-5">
                            @csrf
                            @method('PATCH')   
                            <div class="relative p-4 w-full max-w-full max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Error & Success Notification -->
                                    @include('layouts.notifications')   
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Edit Attendance Details
                                        </h3>
                                    </div>
                                    
                                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                        <div class="max-w-xl">
                                            <div>
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('Attendance Information') }}
                                            </h2>

                                            <img width="100" height="100" class="rounded-full mt-4" src="{{ asset("/storage/$attendance->avatarproof") }}" alt="proof avatar" />

                                                <x-input-label for="name" value="Upload Receipt" />
                                                <x-text-input id="avatarproof" name="avatarproof" type="file"  class="mt-1 block w-full mt-3" :value="old('avatarproof', $attendance->avatarproof)" autofocus autocomplete="off"/>
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
                                                    <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" :value="old('branchname', $attendance->branchname)" required autofocus autocomplete="off" readonly/> 
                                                    <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- username -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="username" :value="__('User Name')" />
                                                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $attendance->username)" required autofocus autocomplete="off" readonly/>
                                                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- firstname -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="firstname" :value="__('First Name')" />
                                                    <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname', $attendance->firstname)" required autofocus autocomplete="given-name" readonly/>
                                                    <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                                </div>
                                            </div>
                                   
                                            <div class="col-span-2 sm:col-span-1">
                                                    <!-- lastname -->
                                                    <div class="form-group mt-4">
                                                    <x-input-label for="lastname" :value="__('Last Name')" />
                                                    <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname', $attendance->lastname)" required autofocus autocomplete="family-name" readonly/>
                                                    <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-span-2 sm:col-span-1 ">
                                                <!-- rpnotes -->
                                                <div class="form-group mt-4">
                                                    <x-input-label for="rpnotes" :value="__('Notes')" />
                                                    <x-text-input id="attnotes" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="textarea" name="attnotes" :value="old('attnotes', $attendance->attnotes)" required autofocus autocomplete="off"/>
                                                    <x-input-error :messages="$errors->get('attnotes')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <!-- i -->
                                                <div class="form-group mt-4">
                                                    <x-text-input id="userid" class="block mt-1 w-full" type="hidden" name="userid" :value="old('userid', $attendance->userid)" required autofocus autocomplete="off" readonly/>
                                                    <x-input-error :messages="$errors->get('userid')" class="mt-2" />
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
   
