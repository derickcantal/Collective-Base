<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    

    public function run(): void
    {
        User::truncate();
        User::Factory()->count(50)->create();
        
    }
}
