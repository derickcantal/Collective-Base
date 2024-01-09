<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use Illuminate\Contracts\Database\Eloquent\Builder;

class SalesEODController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $sales = Sales::where('branchname',auth()->user()->branchname)
                    ->where(function(Builder $builder){
                        $builder->where('posted', "N");
                    })->get();
                   
        $totalsales = collect($sales)->sum('total');

        return view('saleseod.index')
        ->with('totalsales',$totalsales);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $sales = Sales::where('branchname',auth()->user()->branchname)
        ->where(function(Builder $builder){
            $builder->where('posted', "N");
        })->update([
            'posted' => "Y",
            'status' => "Posted",
        ]);

        Sales::query()
            ->where('branchname',auth()->user()->branchname)
            ->where(function(Builder $builder){
                $builder->where('posted', "Y");
            })
            ->each(function ($oldRecord) {
                $newRecord = $oldRecord->replicate();
                $newRecord->setTable('history_sales');
                $newRecord->save();
                $oldRecord->delete();
            });


        return redirect()->route('saleseod.index')
                            ->with('success','EOD Succesful');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
