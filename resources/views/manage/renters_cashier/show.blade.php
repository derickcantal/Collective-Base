<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.manage.navigation')
        </div>
    </div>
    <section>
        <div class="py-8">
            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-8">
                        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                            <!-- Breadcrumb -->
                            <nav class="flex px-5 py-3 text-gray-700 bg-white dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                    <li class="inline-flex items-center">
                                    <a href="{{ route('managecr.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                        </svg>
                                        Renter
                                    </a>
                                    </li>
                                    <li aria-current="page">
                                    <div class="flex items-center">
                                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                        </svg>
                                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ $renter->lastname }}, {{ $renter->firstname }}</span>
                                    </div>
                                    </li>
                                    <li aria-current="page">
                                    <div class="flex items-center">
                                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                        </svg>
                                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Cabinet List</span>
                                    </div>
                                    </li>
                                </ol>
                            </nav>
                            <!-- Error & Success Notification -->
                            @include('layouts.notifications')       
                                
                            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                                <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                                    <form class="flex items-center" action="{{ route('managecr.cabinetadd') }}" method="get">
                                    <x-text-input id="cabuser" class="block mt-1 w-full" type="hidden" name="cabuser" :value="old('userid', $renter->rentersid)" autofocus/>
                                    
                                    <x-primary-button class="ms-4">
                                        <a class="btn btn-primary">Add Cabinet</a>
                                    </x-primary-button>
                                    </form>
                                    
                                </div>
                            </div>
                            <div class="max-w-screen-2xl overflow-x-auto shadow-md sm:rounded-lg mt-4">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                No
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Cabinet No.
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Rent Price
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Branch
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Renter
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Action
                                            </th>
                                            
                                        </tr>
                                    </thead>
                                        @forelse ($cabinets as $cabinet)
                                        
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        
                                            <td class="px-6 py-4">
                                                <x-input-label>{{ ++$i }}</x-input-label>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="cabinetname" :value="$cabinet->cabinetname"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($cabinet->cabinetprice == '' or $cabinet->cabinetprice == 'Null')
                                                    <x-input-label for="cabinetprice" value="0.00"/>
                                                @else
                                                    <x-input-label for="cabinetprice">@php echo number_format($cabinet->cabinetprice, 2); @endphp</x-input-label>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="branchname" :value="$cabinet->branchname"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="email" :value="$cabinet->email"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                @php
                                                    $color = '';
                                                    if ($cabinet->status == 'Active'):
                                                        $color = 'green';
                                                    elseif ($cabinet->status == 'Inactive'):
                                                        $color = 'red';
                                                    endif;
                                                @endphp
                                                        <div class="h-2.5 w-2.5 rounded-full bg-{{ $color; }}-500 me-2"></div> <x-input-label for="status" :value="$cabinet->status"/>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                
                                                <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="{{ route('managecr.cabinetmodify',$cabinet->cabid) }}">Modify</a>
                                                
                                                    
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
                                {!! $cabinets->appends(request()->query())->links() !!}
                            </div>
                        </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>