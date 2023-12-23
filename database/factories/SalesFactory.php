<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sales>
 */
class SalesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rpamount = ['10.00','20.00','30.00','40.00','50.00','100.00','200.00','1000.00'];
        $atype = ['Administrator', 'Supervisor','Cashier','Leesee'];
        $branchname = ['CB Main', 'CB Annex', 'CB Complex', 'CB Plus 1', 'CB Plus 2', 'CB Plus 3'];
        $collected_status = ['Collected','Pending'];
        $status = ['Active', 'Inactive'];

        return [
            'salesname' => Str::random(10),
            'salesavatar'=> 'avatars/product-default.png',
            'cabid' => Arr::random([rand(1, 120)]),
            'cabinetname' => rand(0, 120),
            'productname' => Str::random(10),
            'qty' => Arr::random([rand(1, 250)]),
            'origprice' => Arr::random($rpamount),
            'srp' => Arr::random($rpamount),
            'total' => Arr::random($rpamount),
            'grandtotal' => Arr::random($rpamount),
            'userid' => Arr::random([rand(1, 250)]),
            'username' => Str::random(10),
            'accesstype' => Arr::random($atype) ,
            'branchid' => '1',
            'branchname' => Arr::random($branchname),
            'created_by' => fake()->name(),
            'updated_by' => fake()->name(),
            'collected_status' => Arr::random($collected_status),
            'status' => Arr::random($status),
        ];
    }
}
