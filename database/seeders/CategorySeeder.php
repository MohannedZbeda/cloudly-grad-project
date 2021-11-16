<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class CategorySeeder extends Seeder
{
    
    public function run()
    {
        DB::table('categories')->insert([
            'ar_name' => 'التصنيف رقم واحد',
            'en_name' => 'Category NO1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ],
        [
            'ar_name' => 'التصنيف رقم إثنين',
            'en_name' => 'Category NO2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ],
        [
            'ar_name' => 'التصنيف رقم ثلاثة',
            'en_name' => 'Category NO3',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);    
    }
}
