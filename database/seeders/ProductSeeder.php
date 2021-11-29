<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductSeeder extends Seeder
{
    
    public function run()
    {
        DB::table('products')->insert([
            'category_id' => 1,
            'ar_name' => 'المنتج التابع  للتصنيف رقم واحد',
            'en_name' => 'Category NO1 Product',
            'price' => '10',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('products')->insert([
            'category_id' => 1,
            'ar_name' => 'المنتج  الثاني التابع  للتصنيف رقم واحد',
            'en_name' => 'Category NO1 Product 2',
            'price' => '20',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('products')->insert([
            'category_id' => 2,
            'ar_name' => 'المنتج التابع  للتصنيف رقم إثنين',
            'en_name' => 'Category NO2 Product',
            'price' => '20',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('products')->insert([
            'category_id' => 3,
            'ar_name' => 'المنتج التابع  للتصنيف رقم ثلاثة',
            'en_name' => 'Category NO3 Product',
            'price' => '30',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);    
    }
}
