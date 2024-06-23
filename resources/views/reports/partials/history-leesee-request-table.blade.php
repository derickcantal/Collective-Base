<section>
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                {{ __("Requests") }}
            </div>
            
            <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Sales RID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Profile
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Date Range
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total Sales
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total Collected
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Notes
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Updated By
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @csrf
                        @foreach($sales_requests as $sales_request) 
                        
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <x-input-label for="salesrid" :value="$sales_request->salesrid"/>
                            </th>
                            <td class="px-6 py-4">
                                <x-input-label for="totalcollected">{{ $sales_request->lastname }} , {{ $sales_request->firstname }}</x-input-label>
                                <x-input-label for="totalcollected">Cab No. {{ $sales_request->cabinetname }}</x-input-label>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label><b>{{ Carbon\Carbon::parse($sales_request->rstartdate)->format('Y-m-d') }}</b></x-input-label>
                                <x-input-label> TO <b>{{ Carbon\Carbon::parse($sales_request->renddate)->format('Y-m-d') }}</b></x-input-label>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="totalsales">{{ number_format($sales_request->totalsales, 2) }}</x-input-label>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="totalcollected">{{ number_format($sales_request->totalcollected, 2) }}</x-input-label>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="rnotes" :value="$sales_request->rnotes"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="branchname" :value="$sales_request->branchname"/>
                                <x-input-label for="updated_by" :value="$sales_request->updated_by"/>
                                <x-input-label for="timerecorded" :value="$sales_request->timerecorded"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="status" :value="$sales_request->status"/>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @if(empty($sales_request))
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
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
                <div class="mt-4">
                    {!! $sales_requests->appends(request()->query())->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>	
</section>