<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('sales.index') }}" class="inline-flex items-center text-lg font-high text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"> Sales</a> | 
            <a href="{{ route('attendance.index') }} " class="inline-flex items-center text-lg font-high text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"> Attendance</a>
            @if(auth()->user()->accesstype == 'Cashier') | 
            <a href="{{ route('renter.index') }}" class="inline-flex items-center text-lg font-high text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"> Renters</a> | 
            <u><a href="{{ route('rentercashierrental.index') }}" class="inline-flex items-center text-lg font-high text-white-700 hover:text-blue-600 dark:text-white dark:hover:text-gray-400"> Rental Payments</a></u> 
            @endif
        </h2>
    </x-slot>
    <section>
    <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <form action="" method="POST" class="p-4 md:p-5">
                        @csrf
                        @method('PUT')   
                            <div class="relative p-4 w-full max-w-full max-h-full">
                                <!-- Breadcrumb -->
                                <nav class="flex px-5 py-3 text-gray-700  bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                        <li class="inline-flex items-center">
                                        <a href="{{ route('rentercashierrental.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                            </svg>
                                            Rental Payments
                                        </a>
                                        </li>
                                        <li aria-current="page">
                                        <div class="flex items-center">
                                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Payment History</span>
                                        </div>
                                        </li>
                                        
                                    </ol>
                                </nav>
                                <!-- Error & Success Notification -->
                                @include('layouts.notifications') 
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Modal header -->
                                    
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b border-t rounded-t dark:border-gray-600">
                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $fullname }}<br>
                                            Cabinet No. {{ $cabn }}
                                        </h2>
                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            
                                        </h2>
                                        
                                    </div>

                                    <!-- Modal body -->
  
                                            <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
                                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                        <tr>
                                                            <th scope="col" class="px-6 py-3">
                                                                SRID
                                                            </th>

                                                            <th scope="col" class="px-6 py-3">
                                                                Applicable Month
                                                            </th>
                                                           
                                                            <th scope="col" class="px-6 py-3">
                                                                Proof Image
                                                            </th>
                                                            <th scope="col" class="px-6 py-3">
                                                                Payment Mode
                                                            </th>
                                                            <th scope="col" class="px-6 py-3">
                                                                Total Due
                                                            </th>
                                                            
                                                            
                                                            <th scope="col" class="px-6 py-3">
                                                                Processed By
                                                            </th>
                                                            
                                                        </tr>
                                                    </thead>
                                                            
                                                            @forelse($rentalpayments as $rentalpayment) 
                                                    <tbody>
                                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                            
                                                            <th class="px-6 py-4">
                                                                <x-input-label>{{ ++$i }}</x-input-label>
                                                            </th>

                                                            <td class="px-6 py-4">
                                                                <x-input-label for="rpmonthyear">{{ $rentalpayment->rpmonth }} - {{ $rentalpayment->rpyear }}</x-input-label>
                                                            </td>
                                                          
                                                            <td class="px-6 py-4">
                                                                @php
                                                                    if($rentalpayment->avatarproof == 'avatars/cash-default.jpg'):
                                                                        echo "";
                                                                    endif;
                                                                @endphp
                                                                <img class="w-10 h-10 rounded-sm" src="{{ asset("/storage/$rentalpayment->avatarproof") }}" alt="avatar">
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <x-input-label for="rppaytype" :value="$rentalpayment->rppaytype"/>
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <x-input-label for="rpamount" :value="$rentalpayment->rpamount"/>
                                                            </td>
                                                            
                                                            
                                                            <td class="px-6 py-4">
                                                                <x-input-label for="created_by" :value="$rentalpayment->created_by"/>
                                                                <x-input-label for="timerecorded" :value="$rentalpayment->timerecorded"/>
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
                                                    {!! $rentalpayments->appends(request()->query())->links() !!}
                                                </div>
                                            </div>
                                            
                                    
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>