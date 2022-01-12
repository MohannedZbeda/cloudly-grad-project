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
            'en_name' => 'Balance Wallet',
            'type_name' => 'balance_wallet',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ], [
            'ar_name' => 'محفظة الحجز',
            'en_name' => 'Reservation Wallet',
            'type_name' => 'reservation_wallet',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
