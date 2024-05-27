<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('reports.index') }}" class="inline-flex items-center text-lg font-high text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">Reports</a> 
            @if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor') |
            <u><a href="{{ route('reports.topsalesbranch') }}" class="inline-flex items-center text-lg font-high text-white hover:text-blue-600 dark:text-white dark:hover:text-gray-400">Top Sales Branch</a></u> 
            @endif
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-4">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("Sales") }}
                        </div>
                        <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                            <form class="flex items-center" action="{{ route('reports.searchtopsalesbranch') }}" method="get">
                            <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-2">
                                
                                <div class="grid gap-4 mb-2 grid-cols-2 px-2"> 
                                    <div>
                                        <div class="relative max-w-sm">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                                </svg>
                                            </div>
                                            <input datepicker datepicker-autohide name="startdate" id="startdate" type="text" :value="old('startdate')" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-2" placeholder="Start Date">
                                        </div>
                                    </div>
                                    <div>	
                                        <div class="relative max-w-sm">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                                </svg>
                                            </div>
                                            <input datepicker datepicker-autohide name="enddate" id="enddate" type="text" :value="old('enddate')" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-2" placeholder="End Date">
                                        </div>
                                    </div>
                                </div>
                                
                                
                            
                                <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                                    
                                    <select id="pagerow" name="pagerow" class="flex items-center justify-center w-full px-2 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('pagerow')">
                                        <option value = "10">10</option>    
                                        <option value = "25">25</option>    
                                        <option value = "50">50</option>
                                        <option value = "100">100</option>
                                        <option value = "250">250</option>            
                                    </select>
                                    <select id="orderrow" name="orderrow" class="flex items-center justify-center w-full px-2 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('orderrow')">
                                        <option value = "H-L">H-L</option>    
                                        <option value = "L-H">L-H</option>    
                                        <option value = "A-Z">A-Z</option>
                                        <option value = "Z-A">Z-A</option>        
                                    </select>
                                    <select id="branchname" name="branchname" class="flex items-center justify-center w-full px-2 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('branchname')">
                                        <option value = "All">All</option>        
                                        @foreach($branch as $branches)
                                            <option value = "{{ $branches->branchname}}">{{ $branches->branchname}}</option>
                                        @endforeach
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
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                
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
                                
                                
                                <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
                                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    No
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Product Name
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Qty
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Total
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Branch
                                                </th>
                                            
                                            
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @csrf
                                            @foreach($sales as $sale) 
                                            
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-6 py-4">
                                                    <x-input-label>{{ ++$i }}</x-input-label>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <x-input-label>Cab. No.: <b>{{ $sale->cabinetname }}</b></x-input-label>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <x-input-label for="qty" :value="$sale->qty_sum"/>
                                                </td>
                                                @if($sale->total_sum == 0)
                                                <td class="px-6 py-4">
                                                    <x-input-label for="total">@php echo number_format($sale->total_sum, 2); @endphp****</x-input-label>
                                                </td>
                                                @else
                                                <td class="px-6 py-4">
                                                    <x-input-label for="total">@php echo number_format($sale->total_sum, 2); @endphp</x-input-label>
                                                </td>
                                                @endif
                                                <td class="px-6 py-4">
                                                    <x-input-label for="branchname" :value="$sale->branchname"/>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        @if(empty($sale))
                                        <td scope="row" class="px-6 py-4">
                                            No Records Found.
                                        </td>	
                                        @else
                                        <tfoot>
                                            <tr class="font-semibold text-gray-900 dark:text-white">
                                                <th scope="row" class="px-6 py-3 text-base"></th>
                                                <td class="px-6 py-3"></td>
                                                <td class="px-6 py-3"></td>
                                                <td class="px-6 py-3"></td>
                                            </tr>
                                        </tfoot>
                                        @endif
                                                <td class="px-6 py-3">TOTAL:</td>
                                                <td class="px-6 py-3">{{ $totalqty }}</td>
                                                <td scope="row" class="px-6 py-4"> @php echo number_format($totalsales, 2); @endphp</td>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {!! $sales->appends(request()->query())->links() !!}
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    		
    </x-app-layout>