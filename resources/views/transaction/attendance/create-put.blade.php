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
                        <form action="{{ route('transactionattendance.store') }}" enctype="multipart/form-data" method="POST" class="p-4 md:p-5">
                            @csrf
                            <!-- Error & Success Notification -->
                            @include('layouts.notifications')   
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                <!-- Breadcrumb -->
                                <nav class="flex px-5 py-3 text-gray-700 bg-white dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                        <li class="inline-flex items-center">
                                        <a href="{{ route('transactionattendance.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                            </svg>
                                            Attendace
                                        </a>
                                        </li>
                                        <li aria-current="page">
                                        <div class="flex items-center">
                                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Select Employee</span>
                                        </div>
                                        </li>
                                        <li aria-current="page">
                                        <div class="flex items-center">
                                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Employee Information</span>
                                        </div>
                                        </li>
                                    </ol>
                                </nav>
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        Employee Details 
                                    </h3>
                                </div>
                                <!-- Modal body -->
                                <div class="grid gap-4 mb-4 grid-cols-2">
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- avatar -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="name" value="Upload Employee Attendance Image" />
                                            <x-text-input id="avatarproof" name="avatarproof" type="file"  class="mt-1 block w-full mt-1" :value="old('avatarproof')" autofocus autocomplete="off" required/>
                                            <x-input-error class="mt-2" :messages="$errors->get('avatarproof')" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- branchname -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="branchname" :value="__('Branch Name')" />
                                            <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" :value="old('branchname', $users->branchname)" required autofocus autocomplete="off" readonly/> 
                                            
                                            <x-input-error :messages="$errors->get('branchname')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- i -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="userid" :value="__('User ID')" />
                                            <x-text-input id="userid" class="block mt-1 w-full" type="text" name="userid" :value="old('userid', $users->userid)" required autofocus autocomplete="off" readonly/>
                                            <x-input-error :messages="$errors->get('userid')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- username -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="username" :value="__('Username')" />
                                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $users->username)" required autofocus autocomplete="off" readonly/>
                                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <!-- firstname -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="firstname" :value="__('First Name')" />
                                            <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname', $users->firstname)" required autofocus autocomplete="given-name" readonly/>
                                            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                        </div>
                                    </div>
                            
                                    <div class="col-span-2 sm:col-span-1">
                                            <!-- lastname -->
                                            <div class="form-group mt-4">
                                            <x-input-label for="lastname" :value="__('Last Name')" />
                                            <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname', $users->lastname)" required autofocus autocomplete="family-name" readonly/>
                                            <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                            <!-- email -->
                                            <div class="form-group mt-4">
                                            <x-input-label for="email" :value="__('Last Name')" />
                                            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('lastname', $users->email)" required autofocus autocomplete="off" readonly/>
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1 ">
                                        <!-- rpnotes -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="attnotes" :value="__('Notes')" />
                                            <x-text-input id="attnotes" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" name="attnotes" :value="old('attnotes', $users->attnotes)" autofocus autocomplete="off"/>
                                            <x-input-error :messages="$errors->get('attnotes')" class="mt-2" />
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="flex items-center justify-between col-span-2 sm:col-span-2">
                                        
                                        <x-primary-button class="ms-4">
                                            <a class="btn btn-primary" > Add</a>
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
   
