<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    

    public function run(): void
    {
        User::Factory()->count(100)->create();
        
    }
}
