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
                                                Date Range
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Proof Image
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Total Sales
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Total Collected
                                            </th>
                                            
                                            <th scope="col" class="px-6 py-3">
                                                Created By
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Status
                                            </th>
                                            @if(auth()->user()->accesstype != 'Renters')
                                            <th scope="col" class="px-6 py-3">
                                                Action
                                            </th>
                                            @endif
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
                                                <x-input-label><b>{{ Carbon\Carbon::parse($RenterRequest->rstartdate)->format('Y-m-d') }}</b></x-input-label>
                                                <x-input-label> TO <b>{{ Carbon\Carbon::parse($RenterRequest->renddate)->format('Y-m-d') }}</b></x-input-label>
                                            </td>
                                            <td class="px-6 py-4">
                                                <img class="w-10 h-10 rounded-sm" src="{{ asset("/storage/$RenterRequest->avatarproof") }}" alt="avatar">
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="totalsales">@php echo number_format($RenterRequest->totalsales, 2); @endphp</x-input-label>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="totalcollected">@php echo number_format($RenterRequest->totalcollected, 2); @endphp</x-input-label>
                                            </td>
                                            
                                            <td class="px-6 py-4">
                                                <x-input-label for="branchname" :value="$RenterRequest->branchname"/>
                                                <x-input-label for="created_by" :value="$RenterRequest->created_by"/>
                                                <x-input-label for="timerecorded" :value="$RenterRequest->timerecorded"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="status" :value="$RenterRequest->status"/>
                                            </td>
                                            @if(auth()->user()->accesstype != 'Renters')
                                            <td class="px-6 py-4">
                                                @php
                                                    $btndis='';
                                                    $btnlabel = '';
                                                    $btncolor = '';
                                                    $btntxtcolor = '';

                                                    if($RenterRequest->status == 'For Approval'):
                                                        $btndis = '';
                                                        $btnlabel = 'Process';
                                                        $btncolor = 'blue';
                                                        $btntxtcolor = 'white';
                                                    elseif($RenterRequest->status == 'Completed'):
                                                        $btndis = 'disabled';
                                                        $btnlabel = 'Completed';
                                                        $btncolor = 'green';
                                                        $btntxtcolor = 'white';
                                                    endif;
                                                @endphp
                                                <form action="{{ route('rentersrequests.edit',$RenterRequest->salesrid) }}" method="PUT">
                                                    <x-danger-button class="ms-3 dark:text-{{ $btntxtcolor; }} bg-{{ $btncolor; }}-700 hover:bg-{{ $btncolor; }}-800 focus:outline-none focus:ring-4 focus:ring-{{ $btncolor; }}-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-{{ $btncolor; }}-600 dark:hover:bg-{{ $btncolor; }}-700 dark:focus:ring-{{ $btncolor; }}-800 ">
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
                                <div class="mt-4">
                                    {!! $RenterRequests->appends(request()->query())->links() !!}
                                </div>
            </div>
        </div>
    </div>
</div>	
</section>