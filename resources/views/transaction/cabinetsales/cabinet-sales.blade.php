<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.transaction.navigation')
        </div>
    </div>
	<div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                @csrf
                <!-- Breadcrumb -->
                <nav class="flex px-5 py-3 text-gray-700 bg-white dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                        <a href="{{ route('transactioncabsales.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Cabinet Sales
                        </a>
                        </li>
                        <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                Overview</span>
                        </div>
                        </li>
                        
                        <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                {{ $renter->lastname }}, {{ $renter->firstname }} {{ $renter->middlename }}
                            </span>
                        </div>
                        </li>
                    </ol>
                </nav>
                <!-- Error & Success Notification -->
                @include('layouts.notifications') 
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Renter's Sales Information
                        </h3>
                    </div>
                    <div class="max-w-screen-2xl overflow-x-auto shadow-md sm:rounded-lg " >
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    TOTAL
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th class="px-6 py-4">
                                    <x-input-label>Today Sales</x-input-label>
                                </th>
                                <td class="px-6 py-4">
                                    <x-input-label>{{ number_format($totalsales, 2); }}</x-input-label>
                                </td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th class="px-6 py-4">
                                    <x-input-label>Last 7 Days Total</x-input-label>
                                    <x-input-label>{{ $lwstartweek; }} to {{ $lwendweek; }}</x-input-label>
                                </th>
                                <td class="px-6 py-4">
                                    <x-input-label>{{ number_format($lastweeksales, 2); }}</x-input-label>
                                </td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th class="px-6 py-4">
                                    <x-input-label>Current 7 Days Total</x-input-label>
                                    <x-input-label>{{ $curstartweek; }} to {{ $curendweek; }}</x-input-label>
                                </th>
                                <td class="px-6 py-4">
                                    <x-input-label>{{ number_format($thisweeksales, 2); }}</x-input-label>
                                </td>
                            </tr>
            
                            
                        </tbody>
                    </table>
                    
                </div>
                <div class="py-4">
                    <div class="max-w-screen-2xl overflow-x-auto shadow-md sm:rounded-lg" >
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        MONTHLY SALES
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        TOTAL
                                    </th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <!-- January -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>January - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($jansales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- February -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>February - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($febsales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- March -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>March - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($marsales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- April -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>April - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($aprsales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- May -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>May - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($maysales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- June -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>June - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($junsales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- July -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>July - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($julsales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- August -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>August - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($augsales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- Septermber -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>Septermber - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($septsales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- October -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>October - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($octsales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- November -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>November - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($novsales, 2); }}</x-input-label>
                                    </td>
                                </tr>

                                <!-- December -->
                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4">
                                        <x-input-label>December - {{ $tyear }}</x-input-label>
                                    </th>
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ number_format($decsales, 2); }}</x-input-label>
                                    </td>
                                </tr>
                    
                                
                            </tbody>
                        </table>
                    
                    </div>
                    <div class="flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <a href="{{ route('transactioncabsales.listcabinet',$renter->branchid) }}" class="py-2 px-3 ms-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                            </svg>
                            Close
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
   
