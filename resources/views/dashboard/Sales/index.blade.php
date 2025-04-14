<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.dashboard.navigation')
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
                                <a href="{{ route('dashboardoverview.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                    </svg>
                                    Dashboard 
                                </a>
                                </li>
                                <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                        Sales</span>
                                </div>
                                </li>
                            </ol>
                        </nav>
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
                                        <th scope="col" class="px-6 py-4">
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
                                            Pay IMG 
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Mode
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Sold At
                                        </th>
                                    </tr>
                                </thead>
                                        
                                        @forelse($sales as $sale) 
                                <tbody>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        
                                        <th class="px-6 py-3">
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
                                            @if(auth()->user()->accesstype != 'Renters')
                                            <x-input-label for="created_by" :value="$sale->created_by"/>
                                            @endif
                                            <x-input-label for="timerecorded" :value="$sale->timerecorded"/>

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
                            {!! $sales->appends(request()->query())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
