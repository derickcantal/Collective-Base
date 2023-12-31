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
                                SRID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Profile
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Branch
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
                                Applicable Month
                            </th>
                            
                            <th scope="col" class="px-6 py-3">
                                Updated By
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
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
                                <x-input-label>{{ $rentalpayment->lastname }}, {{ $rentalpayment->firstname }} {{ $rentalpayment->middlename }}</x-input-label>
                                <x-input-label>Cab. No.: <b>{{ $rentalpayment->cabinetname }}</b></x-input-label>
                            </td>
                            <td class="px-6 py-4">
                                <x-input-label for="branchname" :value="$rentalpayment->branchname"/>
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
                                <x-input-label for="rpmonthyear">{{ $rentalpayment->rpmonth }} - {{ $rentalpayment->rpyear }}</x-input-label>
                            </td>
                            
                            <td class="px-6 py-4">
                            <x-input-label for="updated_by" :value="$rentalpayment->updated_by"/>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $btndis='';
                                    $btnlabel = '';
                                    $btncolor = '';
                                    $btntxtcolor = '';

                                    if($rentalpayment->status == 'Unpaid'):
                                        $btndis = '';
                                        $btnlabel = 'Unpaid';
                                        $btncolor = 'blue';
                                        $btntxtcolor = 'white';
                                    elseif($rentalpayment->status == 'Paid'):
                                        $btndis = 'disabled';
                                        $btnlabel = 'Paid';
                                        $btncolor = 'green';
                                        $btntxtcolor = 'white';
                                    endif;
                                @endphp
                                <form action="{{ route('rentalpayments.edit',$rentalpayment->rpid) }}" method="PUT">
                                    <x-danger-button class="ms-3 dark:text-{{ $btntxtcolor; }} bg-{{ $btncolor; }}-700 hover:bg-{{ $btncolor; }}-800 focus:outline-none focus:ring-4 focus:ring-{{ $btncolor; }}-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-{{ $btncolor; }}-600 dark:hover:bg-{{ $btncolor; }}-700 dark:focus:ring-{{ $btncolor; }}-800 ">
                                        {{ $btnlabel; }}
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
                <div class="mt-4">
                    {!! $rentalpayments->appends(request()->query())->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>	
</section>