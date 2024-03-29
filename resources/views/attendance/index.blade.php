<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('sales.index') }}"> Sales</a> | <u> <a href="{{ route('attendance.index') }}"> Attendance</a></u>@if(auth()->user()->accesstype == 'Cashier') | <a href="{{ route('renter.index') }}"> Renters</a> @endif
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-8">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="grid gap-4 mb-4 grid-cols-2">  
                                    <div class="col-span-2 sm:col-span-1">
                                        <div>
                                            @if(auth()->user()->accesstype == 'Cashier')
                                            <x-primary-button class="ms-4">
                                                <a class="btn btn-primary" href="{{ route('attendance.selectemp') }}"> Add Employee</a>
                                            </x-primary-button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1 flex justify-end">
                                        <form action="{{ route('attendance.search') }}" method="get">
                                                <input type="text" name="search" id="table-search-users" class=" text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for Employee">
                                                <x-primary-button class="ms-4">
                                                    Search
                                                </x-primary-button>
                                        </form>
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

                                    @if ($message = Session::get('failed'))
                                    <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
                                        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                        </svg>
                                        <span class="sr-only">Info</span>
                                        <div>
                                            <span class="font-medium">Failed!</span> {{ $message }}
                                        </div>
                                    </div>
                                    @endif
                            </div>    

                                @csrf
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                ID
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Image
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Profile
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Notes
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Info
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                            @forelse($attendance as $att) 
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th class="px-6 py-4">
                                                <x-input-label>{{ ++$i }}</x-input-label>
                                            </th>
                                            <td class="px-6 py-4">
                                                    @php
                                                        if($att->avatarproof == 'avatars/cash-default.jpg'):
                                                            echo "";
                                                        endif;
                                                    @endphp
                                                    <img class="w-10 h-10 rounded-sm" src="{{ asset("/storage/$att->avatarproof") }}" alt="avatar">
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label>{{ $att->lastname }}, {{ $att->firstname }}</x-input-label>
                                                <x-input-label>{{ $att->username }}</x-input-label>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="attnotes" :value="$att->attnotes"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="branchname" :value="$att->branchname"/>
                                                <x-input-label for="created_by" :value="$att->created_by"/>
                                                <x-input-label for="created_at" :value="$att->created_at"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="{{ route('attendance.edit',$att->attid) }}">Modify</a>
                                            </td> 
                                        </tr>
                                        
                                        @empty
                                        <td scope="row" class="px-6 py-4">
                                            No Records Found.
                                        </td>	
                                        @endforelse
                                            
                                    </tbody>
                                </table>
                                <div class="mt-4">
                                    {!! $attendance->appends(request()->query())->links() !!}
                                </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="py-4">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>


    
