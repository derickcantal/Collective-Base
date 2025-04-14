<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.manage.navigation')
        </div>
    </div>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-8">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <!-- Breadcrumb -->
                            <nav class="flex px-5 py-3 text-gray-700  bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                    <li class="inline-flex items-center">
                                    <a href="{{ route('managecr.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                        </svg>
                                        Manage
                                    </a>
                                    </li>
                                    <li aria-current="page">
                                    <div class="flex items-center">
                                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                        </svg>
                                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                            Renters</span>
                                    </div>
                                    </li>
                                </ol>
                            </nav>
                            <!-- Start coding here -->
                            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                                <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                                    <x-primary-button class="ms-4">
                                        <a class="btn btn-primary" href="{{ route('managecr.renterinfo') }}">Create New Renter</a>
                                    </x-primary-button>
                                    
                                    <form class="flex items-center" action="{{ route('managecr.search') }}" method="get">
                                
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
                                                    
                                        <div class="flex items-center w-full space-x-3 md:w-auto">
                                            <x-primary-button class="ms-4">
                                                        Apply Filter
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
                                                        Status
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Action
                                                    </th>
                                                    
                                                </tr>
                                            </thead>
                                                @forelse ($renter as $renters)
                                                
                                            <tbody>
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                
                                                    <td class="px-6 py-4">
                                                        <x-input-label>{{ ++$i }}</x-input-label>
                                                    </td>
                                                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                                        <img class="w-10 h-10 rounded-full" src="{{ asset("/storage/$renters->avatar") }}" alt="avatar">
                                                        <div class="ps-3">
                                                            <div class="text-base font-semibold"><x-input-label for="username" :value="$renters->username"/></div>
                                                            <x-input-label>{{ $renters->lastname }}, {{ $renters->firstname }} {{ $renters->middlename }}</x-input-label>
                                                            <x-input-label for="email" :value="$renters->email"/>
                                                    </th>
                                                   
                                                    <td class="px-6 py-4">
                                                        <x-input-label for="branchname" :value="$renters->branchname"/>
                                                    </td>
                                                    
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center">
                                                        @php
                                                            $color = '';
                                                            if ($renters->status == 'Active'):
                                                                $color = 'green';
                                                            elseif ($renters->status == 'Inactive'):
                                                                $color = 'red';
                                                            endif;
                                                        @endphp
                                                                <div class="h-2.5 w-2.5 rounded-full bg-{{ $color; }}-500 me-2"></div> <x-input-label for="status" :value="$renters->status"/>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        @php
                                                            
                                                        @endphp
                                                        <form action="{{ route('managecr.destroy',$renters->rentersid) }}" method="POST">
                                                            <a class="font-medium text-white-600 dark:text-white-500 hover:underline" href="{{ route('managecr.show',$renters->rentersid) }}">Cabinets</a>
                                                            | <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="{{ route('managecr.edit',$renters->rentersid) }}">Modify</a>
                                                            @csrf
                                                            @method('DELETE')
                                                            @php
                                                            $txtbutton = '';
                                                            $colorbutton = '';
                                                            
                                                            if ($renters->status == 'Active'):
                                                                $txtbutton = 'Decativate';
                                                                
                                                            elseif ($renters->status == 'Inactive'):
                                                                $txtbutton = 'Activate';
                                                                $colorbutton = 'dark:text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800';
                                                            endif
                                                            
                                                            @endphp
                                                            
                                                            <x-danger-button class="ms-3 mt-4 {{ $colorbutton }}">
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
                                        {!! $renter->appends(request()->query())->links() !!}
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>


    
