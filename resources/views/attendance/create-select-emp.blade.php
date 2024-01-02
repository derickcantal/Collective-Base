<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('attendance.index') }}"> Attendance</a> / <u>{{ __('Select Employee') }}</u>
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        
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
                                            Select Employee
                                        </h3>
                                        <div class="col-span-2 sm:col-span-1 flex justify-end">
                                            <form action="{{ route('attendance.searchemp') }}" method="get">
                                                <input type="text" name="searchemp" id="table-search-users" class=" text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for Employee">
                                                    <x-primary-button class="ms-4">
                                                         Search
                                                    </x-primary-button>
                                                
                                            </form>
                                        </div>
                                    </div>
                                    
                                    
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
                                                    Status
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Action
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                             @forelse ($users as $user)
                                             
                                        <tbody>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                               
                                                <td class="px-6 py-4">
                                                    <x-input-label>{{ ++$i }}</x-input-label>
                                                </td>
                                                <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                                    <img class="w-10 h-10 rounded-full" src="{{ asset("/storage/$user->avatar") }}" alt="avatar">
                                                    <div class="ps-3">
                                                        <div class="text-base font-semibold"><x-input-label for="username" :value="$user->username"/></div>
                                                        <x-input-label>{{ $user->lastname }}, {{ $user->firstname }} {{ $user->middlename }}</x-input-label>
                                                        <x-input-label for="email" :value="$user->email"/>
                                                </th>
                                                
                                                <td class="px-6 py-4">
                                                    <x-input-label for="branchname" :value="$user->branchname"/>
                                                </td>
                                                
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                    @php
                                                        $color = '';
                                                        if ($user->status == 'Active'):
                                                            $color = 'green';
                                                        elseif ($user->status == 'Inactive'):
                                                            $color = 'red';
                                                        endif;
                                                    @endphp
                                                            <div class="h-2.5 w-2.5 rounded-full bg-{{ $color; }}-500 me-2"></div> <x-input-label for="status" :value="$user->status"/>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @php
                                                    @endphp
                                                    <form action="{{ route('attendance.putemp',  ['users' => $user->userid]) }}" enctype="multipart/form-data" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        @php
                                                        $btndis='';
                                                        $btnlabel = '';
                                                        $btncolor = '';
                                                        
                                                        if ($user->status == 'Active'):
                                                            $btndis = '';
                                                            $btnlabel = 'SELECT';
                                                            $btncolor = 'blue';
                                                        elseif ($user->status == 'Inactive'):
                                                            $btndis = '';
                                                            $btnlabel = 'SELECT';
                                                            $btncolor = 'blue';
                                                        endif
                                                        
                                                        @endphp
                                                        <x-danger-button class="ms-3 dark:text-white bg-{{ $btncolor; }}-700 hover:bg-{{ $btncolor; }}-800 focus:outline-none focus:ring-4 focus:ring-{{ $btncolor; }}-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-{{ $btncolor; }}-600 dark:hover:bg-{{ $btncolor; }}-700 dark:focus:ring-{{ $btncolor; }}-800 ">
                                                            {{ $btnlabel; }}
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
                                    <div class="mt-4">
                                        {!! $users->appends(request()->query())->links() !!}
                                    </div>
                                        
                                    
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
   