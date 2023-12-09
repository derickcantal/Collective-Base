<x-modal name="add-user" :show="$errors->useradd->isNotEmpty()" focusable>
    <x-slot name="header">
        
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="py-2">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('User Information') }}
                    </h2>
                </div>
                <form method="POST" action="{{ route('register') }}" class="p-6>
                    @csrf

                    <!-- username -->
                    <div>
                        <x-input-label for="username" :value="__('Username')" />
                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->useradd->get('username')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
                        <x-input-error :messages="$errors->useradd->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password" />

                        <x-input-error :messages="$errors->useradd->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                        type="password"
                                        name="password_confirmation" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->useradd->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- firstname -->
                    <div class="mt-4">
                        <x-input-label for="firstname" :value="__('First Name')" />
                        <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" required autofocus autocomplete="given-name" />
                        <x-input-error :messages="$errors->useradd->get('firstname')" class="mt-2" />
                    </div>

                    <!-- middlename -->
                    <div class="mt-4">
                        <x-input-label for="middlename" :value="__('Middle Name')" />
                        <x-text-input id="middlename" class="block mt-1 w-full" type="text" name="middlename" :value="old('middlename')" required autofocus autocomplete="additional-name" />
                        <x-input-error :messages="$errors->useradd->get('username')" class="mt-2" />
                    </div>

                    <!-- lastname -->
                    <div class="mt-4">
                        <x-input-label for="lastname" :value="__('Last Name')" />
                        <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required autofocus autocomplete="family-name" />
                        <x-input-error :messages="$errors->useradd->get('lastname')" class="mt-2" />
                    </div>

                    <!-- birthdate -->
                    <div class="mt-4">
                        <x-input-label for="birthdate" :value="__('Birth Date')" />
                        <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="old('birthdate')" required autofocus autocomplete="bday" />
                        <x-input-error :messages="$errors->useradd->get('birthdate')" class="mt-2" />
                    </div>

                    <!-- branchname -->
                    <div class="mt-4">
                        <x-input-label for="branchname" :value="__('Branch Name')" />
                        <!-- <x-text-input id="branchname" class="block mt-1 w-full" type="text" name="branchname" :value="old('branchname')" required autofocus autocomplete="off" /> -->
                        <select id="branchname" name="branchname" class="form-select mt-1 block w-full" :value="old('branchname')">
                            <option value = "CB Main">CB Main</option>
                            <option value = "CB Annex">CB Annex</option>
                            <option value = "CB Complex">CB Complex</option>
                            <option value = "CB Plus 1">CB Plus 1</option>
                            <option value = "CB Plus 2">CB Plus 2</option>
                            <option value = "CB Plus 3">CB Plus 3</option>
                        </select>
                        <x-input-error :messages="$errors->useradd->get('branchname')" class="mt-2" />
                    </div>

                    
                    <!-- accesstype -->
                    <div class="mt-4">
                        <x-input-label for="accesstype" :value="__('Access Type')" />
                        <!-- <x-text-input id="accesstype" class="block mt-1 w-full" type="text" name="accesstype" :value="old('accesstype')" required autofocus autocomplete="off" /> -->
                        <select id="accesstype" name="accesstype" class="form-select mt-1 block w-full" :value="old('accesstype')">
                            <option value ="Administrator">Administrator</option>
                            <option value ="Supervisor">Supervisor</option>
                            <option value ="Cashier">Cashier</option>
                            <option value ="Leesee">Leesee</option>
                        </select>
                        <x-input-error :messages="$errors->useradd->get('accesstype')" class="mt-2" />
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-primary-button class="ms-3">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                </form>
            
        </div>
    </div>
</x-modal>