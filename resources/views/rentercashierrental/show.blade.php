<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('rentercashierrental.index') }}"> Rental Payments</a></u>/ Payment History
        </h2>
    </x-slot>
    <section>
    <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <form action="" method="POST" class="p-4 md:p-5">
                        @csrf
                        @method('PUT')   
                            <div class="relative p-4 w-full max-w-full max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                                    <!-- Modal header -->
                                    
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b border-t rounded-t dark:border-gray-600">
                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $fullname }}<br>
                                            Cabinet No. {{ $cabn }}
                                        </h2>
                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            
                                        </h2>
                                        
                                    </div>

                                    <!-- Modal body -->
                                        <div class="grid gap-4 mb-4 grid-cols-2">
                                            <div class="col-span-2 sm:col-span-2">
                                                @if ($errors->any())
                                                <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                                <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                                </svg>
                                                <span class="sr-only">Danger</span>
                                                <div>
                                                    <span class="font-medium">Ensure that these requirements are met:</span>
                                                    <ul class="mt-1.5 list-disc list-inside">
                                                        @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif

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
                                            </div>
                                        </div>    
                                            <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
                                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                        <tr>
                                                            <th scope="col" class="px-6 py-3">
                                                                SRID
                                                            </th>

                                                            <th scope="col" class="px-6 py-3">
                                                                Applicable Month
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
                                                                Processed By
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
                                                                <x-input-label for="rpmonthyear">{{ $rentalpayment->rpmonth }} - {{ $rentalpayment->rpyear }}</x-input-label>
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
                                                                <x-input-label for="created_by" :value="$rentalpayment->created_by"/>
                                                                <x-input-label for="timerecorded" :value="$rentalpayment->timerecorded"/>
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
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>