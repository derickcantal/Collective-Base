<section>
	<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-800">
                        <div>
                            <x-primary-button class="ms-4">
                                {{ __('Add New Leesee') }}
                            </x-primary-button>
                        </div>
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative">
                            <input type="text" id="table-search-users" class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for users">
                        </div>
                    </div>
                    @csrf
                    
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                
                                <th scope="col" class="px-6 py-3">
                                    Profile
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Branch
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Access Type
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leesee as $leesees)  
                            

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                                
                                <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <img class="w-10 h-10 rounded-full" src="$leesees->avatar" alt="avatar">
                                    <div class="ps-3">
                                        <div class="text-base font-semibold"><x-input-label for="username" :value="$leesees->username"/></div>
                                        <div class="font-normal text-gray-500"><x-input-label for="email" :value="$leesees->lastname"/></div>
                                    </div>  
                                </th>
                                <td class="px-6 py-4">
                                    <x-input-label for="branchname" :value="$leesees->branchname"/>
                                </td>
                                <td class="px-6 py-4">
                                    <x-input-label for="accesstype" :value="$leesees->accesstype"/>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> <x-input-label for="status" :value="$leesees->status"/>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit user</a> | <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline">Deactivate user</a>
                                </td>
                            </tr>
                            @endforeach
                            @if(empty($products))
							<td scope="row" class="px-6 py-4">
								No Records Found.
							</td>	
							@endif
                        </tbody>

                    </table>
                </div>
  
                
            </div>
        </div>
    </div>
</section>