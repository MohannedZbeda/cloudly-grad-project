<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'zbeda67@gmail.com',
            'username' => 'super_admin',
            'state' => true,
            'password' => Hash::make('11223344'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('role_user')->insert([
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'user_id' => User::where('username', 'super_admin')->first()->id,
            'user_type' => User::class,
        ]);
    }
}
