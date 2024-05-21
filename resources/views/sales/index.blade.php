<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u> <a href="{{ route('sales.index') }}"> Sales</a></u> | <a href="{{ route('attendance.index') }}"> Attendance</a> @if(auth()->user()->accesstype == 'Cashier')| <a href="{{ route('renter.index') }}"> Renters</a> | <a href="{{ route('rentercashierrental.index') }}"> Rental Payments</a> @endif
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
                                    @if(auth()->user()->accesstype == 'Cashier')
                                        <x-primary-button class="ms-4">
                                            <a class="btn btn-primary" href="{{ route('sales.create') }}"> Create New Sales</a>
                                        </x-primary-button>
                                    @else
                                        <div>
                                            
                                        </div>
                                    @endif
                                    
                                    <form class="flex items-center" action="{{ route('sales.search') }}" method="get">
                                
                                    <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                                        
                                        <select id="pagerow" name="pagerow" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('pagerow')">
                                            <option value = "10">10</option>    
                                            <option value = "25">25</option>    
                                            <option value = "50">50</option>
                                            <option value = "100">100</option>
                                            <option value = "250">250</option>            
                                        </select>
                                        <select id="orderrow" name="orderrow" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('orderrow')">
                                            <option value = "H-L">H-L</option>    
                                            <option value = "L-H">L-H</option>    
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
                                                        Search
                                                    </x-primary-button>
                                            </div>
                                        
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
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

                                @csrf
                                <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
                                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    SID
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Image
                                                </th>
                                                
                                                <th scope="col" class="px-6 py-3">
                                                    Product
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Qty
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Price
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Total
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Payment Proof
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Payment Mode
                                                </th>
                                            
                                                <th scope="col" class="px-6 py-3">
                                                    Time Sold
                                                </th>
                                                @if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor')
                                                <th scope="col" class="px-6 py-3">
                                                    Actions
                                                </th>
                                                @endif
                                            </tr>
                                        </thead>
                                                
                                                @forelse($sales as $sale) 
                                        <tbody>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                
                                                <th class="px-6 py-4">
                                                    <x-input-label>{{ ++$i }}</x-input-label>
                                                </th>
                                                <td class="px-6 py-4">
                                                    @php
                                                        if($sale->avatarproof == 'avatars/cash-default.jpg'):
                                                            echo "";
                                                        endif;
                                                    @endphp
                                                    <img class="w-10 h-10 rounded-sm" src="{{ asset("/storage/$sale->salesavatar") }}" alt="avatar">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <x-input-label>{{ $sale->productname }}</x-input-label>
                                                    <x-input-label>Cab. No.: <b>{{ $sale->cabinetname }}</b></x-input-label>
                                                </td>
                                            
                                                <td class="px-6 py-4">
                                                    <x-input-label for="qty" :value="$sale->qty"/>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <x-input-label for="srp">@php echo number_format($sale->srp, 2); @endphp</x-input-label>
                                                </td>
                                                @if($sale->total == 0)
                                                <td class="px-6 py-4">
                                                    <x-input-label for="total">@php echo number_format($sale->total, 2); @endphp****</x-input-label>
                                                </td>
                                                @else
                                                <td class="px-6 py-4">
                                                    <x-input-label for="total">@php echo number_format($sale->total, 2); @endphp</x-input-label>
                                                </td>
                                                @endif
                                               
                                                <td class="px-6 py-4">
                                                    @php
                                                        if($sale->payavatar == 'avatars/cash-default.jpg'):
                                                            echo "";
                                                        endif;
                                                    @endphp
                                                    <img class="w-10 h-10 rounded-sm" src="{{ asset("/storage/$sale->payavatar") }}" alt="avatar">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <x-input-label for="paytype">{{ $sale->paytype }}</x-input-label>
                                                    @if($sale->payref == 'Null')
                                                        <x-input-label for="payref" value=""/>
                                                    @else
                                                        <x-input-label for="payref" :value="$sale->payref"/>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <x-input-label for="branchname" :value="$sale->branchname"/>
                                                    <x-input-label for="timerecorded" :value="$sale->timerecorded"/>
                                                </td>
                                                @if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor')
                                                <td class="px-6 py-4">
                                                    @php
                                                        $btndis='';
                                                        $btnlabel = '';
                                                        $btncolor = '';

                                                        if($sale->status == 'Unposted'):
                                                            $btndis = '';
                                                            $btnlabel = 'Modify';
                                                            $btncolor = 'green';
                                                        elseif($sale->status == 'Posted'):
                                                            $btndis = 'disabled';
                                                            $btnlabel = 'Modify';
                                                            $btncolor = 'green';
                                                        endif;
                                                    @endphp
                                                    <form action="{{ route('sales.edit',$sale->salesid) }}" method="PUT">
                                                        <x-danger-button class="ms-3 dark:text-white bg-{{ $btncolor; }}-700 hover:bg-{{ $btncolor; }}-800 focus:outline-none focus:ring-4 focus:ring-{{ $btncolor; }}-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-{{ $btncolor; }}-600 dark:hover:bg-{{ $btncolor; }}-700 dark:focus:ring-{{ $btncolor; }}-800 ">
                                                            {{ $btnlabel; }}
                                                        </x-danger-button>
                                                    </form>
                                                </td>
                                                @endif
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
                                    {!! $sales->appends(request()->query())->links() !!}
                                </div>
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>


    
