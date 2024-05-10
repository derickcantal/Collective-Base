<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        <u><a href="{{ route('myrental.index') }}"> Rental</a></u> / Rental Payments / Cabinet No. {{ $cabid }}
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-8">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                                <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                                    <div>
                                        
                                    </div>                                    
                                    <form class="flex items-center" action="{{ route('myrental.search') }}" method="get">
                                
                                    <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                                        
                                        <select id="pagerow" name="pagerow" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('pagerow')">
                                            <option value = "10">10</option>    
                                            <option value = "25">25</option>    
                                            <option value = "50">50</option>
                                            <option value = "100">100</option>
                                            <option value = "250">250</option>            
                                        </select>
                                        <select id="orderrow" name="orderrow" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('orderrow')">
                                            <option value = "A-Z">A-Z</option>
                                            <option value = "Z-A">Z-A</option>        
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
                                                <a class="btn btn-primary" href="{{ route('myrental.search') }}">Search</a>
                                            </x-primary-button>   
                                        </div>
                                        
                                    </div>
                                    </form>
                                    
                                </div>
                            </div>
                            <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg mt-4" >
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
                </div>
            </div>
        </div>
    </section>
</x-app-layout>