<section>
	<div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
					<div class="p-6 text-gray-900 dark:text-gray-100">
						{{ __("Sales") }}
					</div>
					<div class="grid gap-4 mb-4 grid-cols-2">  
						<div class="col-span-2 sm:col-span-1">
							<div>
								
							</div>
						</div>
						<div class="col-span-2 sm:col-span-1 flex justify-end">
							<form action="reports.search" method="get">
								<input type="text" name="search" id="search" class=" text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for Sales">
									<x-primary-button class="mt-4">
										Search
									</x-primary-button>
								
							</form>
						</div>
					</div>
					
					<div class="max-w-7xl overflow-x-auto shadow-md sm:rounded-lg " >
						<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
							<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
								<tr>
									<th scope="col" class="px-6 py-3">
										Sales ID
									</th>
									<th scope="col" class="px-6 py-3">
										Image
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
                                        Payment Proof
									</th>
									<th scope="col" class="px-6 py-3">
										Payment Mode
									</th>
									<th scope="col" class="px-6 py-3">
										Branch
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
									<th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
										<x-input-label for="salesid" :value="$sale->salesid"/>
									</th>
									<td class="px-6 py-4">
										@php
											if($sale->avatarproof == 'avatars/cash-default.jpg'):
												echo "";
											endif;
										@endphp
										<img class="w-10 h-10 rounded-sm" src="{{ asset("/storage/$sale->salesavatar") }}" alt="avatar">
									</td>
									<td class="px-6 py-4">
										<x-input-label for="productname" :value="$sale->productname"/>
										<x-input-label>Cab. No.: <b>{{ $sale->cabinetname }}</b></x-input-label>
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
										@php
											if($sale->payavatar == 'avatars/cash-default.jpg'):
												echo "";
											endif;
										@endphp
										<img class="w-10 h-10 rounded-sm" src="{{ asset("/storage/$sale->payavatar") }}" alt="avatar">
									</td>
									<td class="px-6 py-4">
										<x-input-label for="paytype" :value="$sale->paytype"/>
										<x-input-label for="payref" :value="$sale->payref"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="branchname" :value="$sale->branchname"/>
									</td>
									<td class="px-6 py-4">
										<x-input-label for="created_by" :value="$sale->created_by"/>
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
								</tr>
							</tfoot>
							@endif
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