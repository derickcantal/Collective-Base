<section>
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                {{ __("Attendance") }}
            </div>
            
            <div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                ID
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Profile
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Branch
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Notes
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Added By
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Time
                                            </th>
                                           
                                        </tr>
                                    </thead>
                                            
                                            @forelse($attendance as $att) 
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            
                                            <th class="px-6 py-4">
                                                <x-input-label>{{ ++$i }}</x-input-label>
                                            </th>
                                            <td class="px-6 py-4">
                                                <x-input-label>{{ $att->lastname }}, {{ $att->firstname }}</x-input-label>
                                                <x-input-label>{{ $att->username }}</x-input-label>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="branchname" :value="$att->branchname"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="attnotes" :value="$att->attnotes"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="created_by" :value="$att->created_by"/>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input-label for="created_at" :value="$att->created_at"/>
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
                                    {!! $attendance->appends(request()->query())->links() !!}
                                </div>
            </div>
        </div>
    </div>
</div>	
</section>