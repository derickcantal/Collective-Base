<section>
	<div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
					<div class="p-6 text-gray-900 dark:text-gray-100">
						{{ __("Sales") }}
					</div>

					
					<div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
						<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
							<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
								<tr>
									<th scope="col" class="px-6 py-3">
										SID
									</th>
									<th scope="col" class="px-6 py-3">
										Product
									</th>
									
									<th scope="col" class="px-6 py-3">
										Branch
									</th>
									<th scope="col" class="px-6 py-3">
										Image
									</th>
									<th scope="col" class="px-6 py-3">
										Qty
									</th>
									<th scope="col" class="px-6 py-3">
										Price
									</th>
									<th scope="col" class="px-6 py-3">
										Total
									</th>
									
									<th scope="col" class="px-6 py-3">
										Cashier
									</th>
									<th scope="col" class="px-6 py-3">
										Time Sold
									</th>
									
								</tr>
							</thead>
									
									@forelse($sales as $sale) 
							<tbody>
								<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
									
									<th class="px-6 py-4">
										<x-input-label>{{ ++$i }}</x-input-label>
									</th>
									<td class="px-6 py-4">
										<x-input-label>{{ $sale->productname }}</x-input-label>
										<x-input-label>Cab. No.: <b>{{ $sale->cabinetname }}</b></x-input-label>
									</td>
									
									<td class="px-6 py-4">
										<x-input-label for="branchname" :value="$sale->branchname"/>
									</td>
									<td class="px-6 py-4">
										@php
											if($sale->avatarproof == 'avatars/cash-default.jpg'):
												echo "";
											endif;
										@endphp
										<img class="w-10 h-10 rounded-sm" src="{{ asset("/storage/$sale->salesavatar") }}" alt="avatar">
									</td>
									<td class="px-6 py-4">
										<x-input-label for="qty" :value="$sale->qty"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="srp" :value="$sale->srp"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="total">{{ $sale->total }}</x-input-label>
									</td>
									
									<td class="px-6 py-4">
										<x-input-label for="created_by" :value="$sale->created_by"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="created_at" :value="$sale->created_at"/>
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
							{!! $sales->appends(request()->query())->links() !!}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>		
</section>