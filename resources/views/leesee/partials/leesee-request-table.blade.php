<section>
<div class="py-6">
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
                                Branch Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Cabinet Name
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
                                Full Name
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
                                <x-input-label for="branchname" :value="$sales_request->branchname"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="cabinetname" :value="$sales_request->cabinetname"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="totalsales" :value="$sales_request->totalsales"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="totalcollected" :value="$sales_request->totalcollected"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="rnotes" :value="$sales_request->rnotes"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="lastname" :value="$sales_request->lastname"/>, <x-input-label for="firstname" :value="$sales_request->firstname"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="updated_by" :value="$sales_request->updated_by"/>
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
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>	
</section>