<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class WalletTypeSeeder extends Seeder
{
    
    public function run()
    {
        DB::table('wallet_types')->insert([
            'ar_name' => 'المحفظة الإفتراضية',
            'en_name' => 'Default Wallet',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('wallet_types')->insert([
            'ar_name' => 'محفظة الدولار',
            'en_name' => 'Dollar Wallet',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

    }
}
