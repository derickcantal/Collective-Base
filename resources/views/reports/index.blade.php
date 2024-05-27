<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <u><a href="{{ route('reports.index') }}" class="inline-flex items-center text-lg font-high text-white hover:text-blue-600 dark:text-white dark:hover:text-gray-400">Reports</a></u>
            @if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor') |
            <a href="{{ route('reports.topsalesbranch') }}" class="inline-flex items-center text-lg font-high text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">Top Sales Branch</a> 
            @endif
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include('reports.partials.history-sales-table')
            </div>
        </div>
    </div>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include('reports.partials.history-leesee-request-table')
            </div>
        </div>
    </div>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include('reports.partials.history-rental-payments-table')
            </div>
        </div>
    </div>
    @if(auth()->user()->accesstype != 'Renters')
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include('reports.partials.history-attendance-table')
            </div>
        </div>
    </div>
    @endif


    
</x-app-layout>
