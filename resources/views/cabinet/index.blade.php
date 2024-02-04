<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('branch.index') }}"> Branch</a> | <u><a href="{{ route('cabinet.index') }}"> Cabinet</a> </u>
        </h2>
    </x-slot>
    <section>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-8">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                
                                <div class="grid gap-4 mb-4 grid-cols-2">  
                                        <div class="col-span-2 sm:col-span-1">
                                            <div>
                                                <x-primary-button class="ms-4 mt-4">
                                                    <a class="btn btn-primary" href="{{ route('cabinet.create') }}"> Create New Cabinet</a>
                                                </x-primary-button>
                                            </div>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1 flex justify-end">
                                            <form action="{{ route('cabinet.search') }}" method="get">
                                                    <input type="text" name="search" id="search" class=" text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for branch">
                                                    <x-primary-button class="ms-4 mt-4">
                                                        Search
                                                    </x-primary-button>
                                            </form>
                                        </div>
                                </div>
                        
                                    @if ($message = Session::get('success'))
                                    <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-medium">Success!</span> {{ $message }}
                                    </div>
                                    </div>
                                    @endif
                                    @if ($message = Session::get('failed'))
                                    <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
                                        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                        </svg>
                                        <span class="sr-only">Info</span>
                                        <div>
                                            <span class="font-medium">Failed!</span> {{ $message }}
                                        </div>
                                    </div>
                                    @endif
                                </div>    

                                    @csrf
                                    <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg">
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
                                                        Created by
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
                                                            <x-input-label for="cabinetprice" :value="$cabinet->cabinetprice"/>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <x-input-label for="branchname" :value="$cabinet->branchname"/>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <x-input-label for="email" :value="$cabinet->email"/>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <x-input-label for="created_by" :value="$cabinet->created_by"/>
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
                                                        
                                                        <form action="{{ route('cabinet.destroy',$cabinet->cabid) }}" method="POST">
                                                        <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="{{ route('cabinet.edit',$cabinet->cabid) }}">Modify</a>
                                                            @csrf
                                                            @method('DELETE')
                                                            @php
                                                            $txtbutton = '';
                                                            $colorbutton = '';
                                                            
                                                            if ($cabinet->status == 'Active'):
                                                                $txtbutton = 'Decativate';
                                                                $colorbutton = 'dark:text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800';
                                                            elseif ($cabinet->status == 'Inactive'):
                                                                $txtbutton = 'Activate';
                                                                $colorbutton = 'dark:text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800';
                                                            endif
                                                            
                                                            @endphp
                                                            
                                                            <x-danger-button class="ms-3 {{ $colorbutton }}">
                                                                {{ $txtbutton }}
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
                                    </div>
                                    <div class="mt-4">
                                        {!! $cabinets->appends(request()->query())->links() !!}
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>


    
