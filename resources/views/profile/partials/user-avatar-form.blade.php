<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Avatar') }}
        </h2>

        <img width="100" height="100" class="rounded-full mt-4" src="{{ asset("/storage/$user->avatar") }}" alt="user avatar" />

        
    </header>

    @if(session('message'))
        <div class="text-red-500">
            {{ session('message') }}
        </div>
    @endif

    <form method="post" enctype="multipart/form-data" action="{{ route('profile.avatar') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <div>
            <x-input-label for="name" value="Change/Update Avatar" />
            <x-text-input id="avatar" name="avatar" type="file"  class="mt-1 block w-full mt-3" :value="old('avatar', $user->avatar)" autofocus autocomplete="avatar" />
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="flex items-center gap-4" mt-4>
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>

    
</section>
