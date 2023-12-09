<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-8">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                
                                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                    <div class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-800">
                                        <div>
                                            <x-primary-button class="ms-4">
                                                <a class="btn btn-primary" href="{{ route('users.create') }}"> Create New user</a>
                                            </x-primary-button>
                                        </div>
                                    
                                        <label for="table-search" class="sr-only">Search</label>
                                        <div class="relative">
                                            <input type="text" id="table-search-users" class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for users">
                                        </div>
                                    </div>
                        
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
                                    

                                    @csrf
                                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    No
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Profile
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Branch
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Access Type
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Status
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Action
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                             @foreach ($user as $users)
                                        
                                        <tbody>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                               
                                                <td class="px-6 py-4">
                                                    <x-input-label for="userid" value=" {{ ++$i }}"/>
                                                </td>
                                                <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                                    <img class="w-10 h-10 rounded-full" src="$users->avatar" alt="avatar">
                                                    <div class="ps-3">
                                                        <div class="text-base font-semibold"><x-input-label for="username" :value="$users->username"/></div>
                                                        <div class="font-normal text-gray-500"><x-input-label for="email" :value="$users->lastname"/></div>
                                                    </div>  
                                                </th>
                                                <td class="px-6 py-4">
                                                    <x-input-label for="branchname" :value="$users->branchname"/>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <x-input-label for="accesstype" :value="$users->accesstype"/>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> <x-input-label for="status" :value="$users->status"/>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    
                                                    <form method="POST" action="{{ route('users.destroy',$users->userid) }}" >
                                                    <a class="font-medium text-green-600 dark:text-green-500 hover:underline" href="{{ route('users.show',$users->userid) }}">Show</a>
                                                    |
                                                    <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="{{ route('users.edit',$users->userid) }}">Modify</a>
                                                    @csrf
                                                    @method('DELETE')
                                                        <x-danger-button class="ms-3">
                                                            {{ __('Delete Account') }}
                                                        </x-danger-button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @if(empty($users))
                                            <td scope="row" class="px-6 py-4">
                                                No Records Found.
                                            </td>	
                                            @endif
                                        </tbody>
                                    </table>
                                    {!! $user->links() !!}
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>


    
