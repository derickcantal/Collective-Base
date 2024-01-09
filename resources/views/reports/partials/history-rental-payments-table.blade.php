<section>
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                {{ __("Rental Payements") }}
            </div>
            
            <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                RP ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Username
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Full Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Amount Paid
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Payment Type
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Applicable Month
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Notes
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Branch
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Created at
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Created by
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @csrf
                        @foreach($rentalpayments as $rental) 
                        
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                <x-input-label for="rpid" :value="$rental->rpid"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="username" :value="$rental->username"/>
                            </td>
                            <td class="px-6 py-4">
                            <x-input-label for="lastname" :value="$rental->lastname"/>, <x-input-label for="firstname" :value="$rental->firstname"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="rpamount" :value="$rental->rpamount"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="rppaytype" :value="$rental->rppaytype"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="rpmonthyear" :value="$rental->rpmonthyear"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="rpnotes" :value="$rental->rpnotes"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="branchname" :value="$rental->branchname"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="created_at" :value="$rental->created_at"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="created_by" :value="$rental->created_by"/>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="status" :value="$rental->status"/>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @if(empty($rental))
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