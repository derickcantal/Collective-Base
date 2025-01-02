<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.manage.navigation')
        </div>
    </div>
<div class="py-8">
	<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
			<div class="py-8">
				<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
					<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <!-- Breadcrumb -->
                        <nav class="flex px-5 py-3 text-gray-700  bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                <li class="inline-flex items-center">
                                <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                    </svg>
                                    Users
                                </a>
                                </li>
                            </ol>
                        </nav>
                        <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                            <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                                <x-primary-button class="ms-4">
                                    <a class="btn btn-primary" href="{{ route('manageuser.create') }}"> Create New user</a>
                                </x-primary-button>
                                
                                <form class="flex items-center" action="{{ route('manageuser.search') }}" method="get">
                                    @csrf
                                    <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                                        
                                        <select id="pagerow" name="pagerow" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('pagerow')">
                                            <option value = "10">10</option>    
                                            <option value = "25">25</option>    
                                            <option value = "50">50</option>
                                            <option value = "100">100</option>
                                            <option value = "250">250</option>            
                                        </select>
                                        <select id="orderrow" name="orderrow" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('orderrow')">
                                            <option value = "asc">A-Z</option>
                                            <option value = "desc">Z-A</option>   
                                        </select>
                                        <div class="w-full md:w-1/2">
                                            <label for="simple-search" class="sr-only">Search</label>
                                            <div class="relative w-full">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <input type="text" name="search" id="search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search" >
                                            </div>
                                        </div>              
                                        <div class="flex items-center w-full space-x-3 md:w-auto">
                                            <x-primary-button class="ms-4">
                                                        Search
                                                    </x-primary-button>
                                            </div>
                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                            
                        <!-- Error & Success Notification -->
                        @include('layouts.notifications') 

                        @csrf
                        <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg mt-4" >
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
                                    @forelse ($user as $users)
                                    
                                <tbody>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    
                                        <td class="px-6 py-4">
                                            <x-input-label>{{ ++$i }}</x-input-label>
                                        </td>
                                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <img class="w-10 h-10 rounded-full" src="{{ asset("/storage/$users->avatar") }}" alt="avatar">
                                            <div class="ps-3">
                                                
                                                <x-input-label>{{ $users->lastname }}, {{ $users->firstname }} {{ $users->middlename }}</x-input-label>
                                                <x-input-label for="email" :value="$users->email"/>
                                        </th>
                                        <td class="px-6 py-4">
                                            <x-input-label for="branchname" :value="$users->branchname"/>
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-input-label for="accesstype" :value="$users->accesstype"/>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                            @php
                                                $color = '';
                                                if ($users->status == 'Active'):
                                                    $color = 'green';
                                                elseif ($users->status == 'Inactive'):
                                                    $color = 'red';
                                                endif;
                                            @endphp
                                                    <div class="h-2.5 w-2.5 rounded-full bg-{{ $color; }}-500 me-2"></div> <x-input-label for="status" :value="$users->status"/>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            
                                            <form action="{{ route('manageuser.destroy',$users->userid) }}" method="POST">
                                            <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="{{ route('manageuser.edit',$users->userid) }}">Modify</a>
                                                @csrf
                                                @method('DELETE')
                                                @php
                                                $txtbutton = '';
                                                $colorbutton = '';
                                                
                                                if ($users->status == 'Active'):
                                                    $txtbutton = 'Decativate';
                                                    
                                                elseif ($users->status == 'Inactive'):
                                                    $txtbutton = 'Activate';
                                                    $colorbutton = 'dark:text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800';
                                                endif
                                                
                                                @endphp
                                                
                                                <x-danger-button class="ms-3 {{ $colorbutton }}">
                                                    {{ $txtbutton }}
                                                </x-danger-button>
                                            </form>
                                        </td>
                                    </tr>
                                
                                    @empty
                                    <td scope="row" class="px-6 py-4">
                                        No Records Found.
                                    </td>	
                                    @endforelse
                                        
                                </tbody>
                                
                            </table>
                        </div>
                        <div class="mt-4">
                        {{ $user->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
