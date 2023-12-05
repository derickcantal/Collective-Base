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
										Sales ID
									</th>
									<th scope="col" class="px-6 py-3">
										Product Name
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
										Grand Total
									</th>
									<th scope="col" class="px-6 py-3">
										Branch
									</th>
									<th scope="col" class="px-6 py-3">
										Cashier
									</th>
									<th scope="col" class="px-6 py-3">
										Sold At
									</th>
								</tr>
							</thead>
							<tbody>
								@csrf
								@foreach($sales as $sale) 
								
								<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
									<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
										<x-input-label for="salesid" :value="$sale->salesid"/>
									</th>
									<td class="px-6 py-4">
										<x-input-label for="productname" :value="$sale->productname"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="qty" :value="$sale->qty"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="srp" :value="$sale->srp"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="total" :value="$sale->total"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="grandtotal" :value="$sale->grandtotal"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="branchname" :value="$sale->branchname"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="username" :value="$sale->username"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="created_at" :value="$sale->created_at"/>
									</td>
								</tr>
								@endforeach
							</tbody>
							@if(empty($sale))
							<td scope="row" class="px-6 py-4">
								No Records Found.
							</td>	
							@else
							<tfoot>
								<tr class="font-semibold text-gray-900 dark:text-white">
									<th scope="row" class="px-6 py-3 text-base"></th>
									<td class="px-6 py-3"></td>
									<td class="px-6 py-3"></td>
									<td class="px-6 py-3"></td>
									<td class="px-6 py-3"></td>
									<td class="px-6 py-3"></td>
									<td class="px-6 py-3"></td>
									<td class="px-6 py-3"></td>
									<td class="px-6 py-3"></td>
									<td class="px-6 py-3"></td>
								</tr>
							</tfoot>
							@endif
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>		
</section>