<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.transaction.navigation')
        </div>
    </div>
<div class="py-8">
	<div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
			<div class="py-8">
				<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
					<div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                        <!-- Error & Success Notification -->
                        @include('layouts.notifications') 
                        @csrf
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Branch
                                    </th>
                                    
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                    
                                </tr>
                            </thead>
                                    @forelse ($branches as $branch)
                                    
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    
                                    <td class="px-6 py-4">
                                        <x-input-label>{{ ++$i }}</x-input-label>
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-input-label for="branchname" :value="$branch->branchname"/>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                        @php
                                            $color = '';
                                            if ($branch->status == 'Active'):
                                                $color = 'green';
                                            elseif ($branch->status == 'Inactive'):
                                                $color = 'red';
                                            endif;
                                        @endphp
                                                <div class="h-2.5 w-2.5 rounded-full bg-{{ $color; }}-500 me-2"></div> <x-input-label for="status" :value="$branch->status"/>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                        @endphp
                                        <form action="{{ route('transactionrequest.selectcabinet', ['branch' => $branch->branchid]) }}" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            @method('PUT')
                                            @php
                                            $btndis='';
                                            $btnlabel = '';
                                            $btncolor = '';
                                            
                                            if ($branch->status == 'Active'):
                                                $btndis = '';
                                                $btnlabel = 'SELECT';
                                                $btncolor = 'blue';
                                            elseif ($branch->status == 'Inactive'):
                                                $btndis = '';
                                                $btnlabel = 'SELECT';
                                                $btncolor = 'blue';
                                            endif
                                            
                                            @endphp
                                            <x-danger-button class="ms-3 dark:text-white bg-{{ $btncolor; }}-700 hover:bg-{{ $btncolor; }}-800 focus:outline-none focus:ring-4 focus:ring-{{ $btncolor; }}-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-{{ $btncolor; }}-600 dark:hover:bg-{{ $btncolor; }}-700 dark:focus:ring-{{ $btncolor; }}-800 ">
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
                            {!! $branches->appends(request()->query())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>