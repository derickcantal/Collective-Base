<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('renters.index') }}" class="inline-flex items-center text-lg font-high text-white hover:text-blue-600 dark:text-white dark:hover:text-gray-400">Renters</a></u> | 
            <a href="{{ route('rentersrequests.index') }}" class="inline-flex items-center text-lg font-high text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">Renters Requests</a> |
            <a href="{{ route('rentalpayments.index') }}" class="inline-flex items-center text-lg font-high text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"> Rental Payments</a>  
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-8">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                                <!-- Breadcrumb -->
                                <nav class="flex px-5 py-3 text-gray-700  bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                        <li class="inline-flex items-center">
                                        <a href="{{ route('renters.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                            </svg>
                                            Renters
                                        </a>
                                        </li>
                                        <li aria-current="page">
                                        <div class="flex items-center">
                                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ $renter->username }}</span>
                                        </div>
                                        </li>
                                        <li aria-current="page">
                                        <div class="flex items-center">
                                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Cabinet List</span>
                                        </div>
                                        </li>
                                    </ol>
                                </nav>
                                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                    
                                    <div class="relative p-4 w-full max-w-full max-h-full">
                                        <!-- Error & Success Notification -->
                                        @include('layouts.notifications') 
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                            <!-- Modal header -->
                                            <div class="flex items-center justify-between p-4 md:p-5 border-b  dark:border-gray-600">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    Cabinet List
                                                </h3>
                                            </div>
                                        </div>
                                        
                                    </div>
                                        
                                </div>
                                <!-- Modal body -->
                                <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg mt-3">
                                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    No
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Cabinet No.
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Rent Price
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Created by
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Status
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Action
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                            @forelse ($cabinets as $cabinet)
                                            
                                        <tbody>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            
                                                <td class="px-6 py-4">
                                                    <x-input-label>{{ ++$i }}</x-input-label>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <x-input-label for="cabinetname" :value="$cabinet->cabinetname"/>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <x-input-label for="email" :value="$cabinet->email"/>
                                                    <x-input-label for="cabinetprice">{{ number_format($cabinet->cabinetprice, 2) }}</x-input-label>
                                                </td>
                                            
                                                
                                                <td class="px-6 py-4">
                                                    <x-input-label for="branchname" :value="$cabinet->branchname"/>
                                                    <x-input-label for="created_by" :value="$cabinet->created_by"/>
                                                    <x-input-label for="timerecorded" :value="$cabinet->timerecorded"/>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                    @php
                                                        $color = '';
                                                        if ($cabinet->status == 'Active'):
                                                            $color = 'green';
                                                        elseif ($cabinet->status == 'Inactive'):
                                                            $color = 'red';
                                                        endif;
                                                    @endphp
                                                            <div class="h-2.5 w-2.5 rounded-full bg-{{ $color; }}-500 me-2"></div> <x-input-label for="status" :value="$cabinet->status"/>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    
                                                    <form action="{{ route('cabinet.destroy',$cabinet->cabid) }}" method="POST">
                                                    <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="{{ route('renters.editcabinet',$cabinet->cabid) }}">Modify</a>
                                                        @csrf
                                                        @method('DELETE')
                                                        @php
                                                        $txtbutton = '';
                                                        $colorbutton = '';
                                                        
                                                        if ($cabinet->status == 'Active'):
                                                            $txtbutton = 'Decativate';
                                                            $colorbutton = 'dark:text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800';
                                                        elseif ($cabinet->status == 'Inactive'):
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
                                    {!! $cabinets->appends(request()->query())->links() !!}
                                </div>
                            </div>
                        </div>    
                    </div>    
                </div>
            </div>
        </div>
    </section>
</x-app-layout>