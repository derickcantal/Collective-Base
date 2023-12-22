<section>
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                {{ __("Requests") }}
            </div>
            
            <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
            @csrf
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
                                                Total Sales
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Total Collected
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Proof Image
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Updated By
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Action
                                            </th>
                                            
                                        </tr>
                                    </thead>
                                            
                                            @forelse($RenterRequests as $RenterRequest) 
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            
                                            <th class="px-6 py-4">
                                                <div class="text-base font-semibold"><x-input-label for="username" :value="$RenterRequest->username"/></div>
                                                <x-input-label>{{ ++$i }}</x-input-label>
                                            </th>
                                            <td class="px-6 py-4">
                                                <x-input-label>{{ $RenterRequest->lastname }}, {{ $RenterRequest->firstname }} {{ $RenterRequest->middlename }}</x-input-label>
                                                <x-input-label>Cab. No.: <b>{{ $RenterRequest->cabinetname }}</b></x-input-label>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="branchname" :value="$RenterRequest->branchname"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="totalsales" :value="$RenterRequest->totalsales"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="totalcollected" :value="$RenterRequest->totalcollected"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <img class="w-10 h-10 rounded-sm" src="{{ asset("/storage/$RenterRequest->avatarproof") }}" alt="avatar">
                                            </td>
                                            <td class="px-6 py-4">
                                            <x-input-label for="updated_by" :value="$RenterRequest->updated_by"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="status" :value="$RenterRequest->status"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $btndis='';
                                                    $btnlabel = '';
                                                    $btncolor = '';

                                                    if($RenterRequest->status == 'Pending'):
                                                        $btndis = '';
                                                        $btnlabel = 'Process';
                                                        $btncolor = 'red';
                                                    elseif($RenterRequest->status == 'Completed'):
                                                        $btndis = 'disabled';
                                                        $btnlabel = 'Completed';
                                                        $btncolor = 'green';
                                                    endif;
                                                @endphp
                                                <form action="{{ route('rentersrequests.edit',$RenterRequest->salesrid) }}" method="PUT">
                                                    <x-primary-button class="ms-3 dark:text-white bg-{{ $btncolor; }}-700 hover:bg-{{ $btncolor; }}-800 focus:outline-none focus:ring-4 focus:ring-{{ $btncolor; }}-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-{{ $btncolor; }}-600 dark:hover:bg-{{ $btncolor; }}-700 dark:focus:ring-{{ $btncolor; }}-800 ">
                                                        {{ $btnlabel; }}
                                                    </x-primary-button>
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
                                    {!! $RenterRequests->appends(request()->query())->links() !!}
                                </div>
            </div>
        </div>
    </div>
</div>	
</section>