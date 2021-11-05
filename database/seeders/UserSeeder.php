<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Mohanned Zbeda',
            'email' => 'mo@zbeda.com',
            'username' => 'zbeda123',
            'state' => true,
            'password' => Hash::make('11223344'),
        ]);
    }
}
