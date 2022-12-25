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
            'name' => 'المنتج التابع  للتصنيف رقم واحد',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('products')->insert([
            'category_id' => 1,
            'name' => 'المنتج  الثاني التابع  للتصنيف رقم واحد',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('products')->insert([
            'category_id' => 2,
            'name' => 'المنتج التابع  للتصنيف رقم إثنين',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('products')->insert([
            'category_id' => 3,
            'name' => 'المنتج التابع  للتصنيف رقم ثلاثة',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);    
    }
}
