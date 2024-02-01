<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        <u><a href="{{ route('renters.index') }}"> Renters</a></u> / {{ __('Renter') }} / {{ $renter->username }}
        </h2>
    </x-slot>
    <section>
    <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="relative p-4 w-full max-w-full max-h-full">
                            <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        Cabinet List
                                    </h3>
                                </div>
                            </div>

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
                                                        <x-input-label for="cabinetprice" :value="$cabinet->cabinetprice"/>
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
    </section>
</x-app-layout>