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
            'name' => 'التصنيف رقم واحد',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'name' => 'التصنيف رقم إثنين',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'name' => 'التصنيف رقم ثلاثة',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        
    }
}
