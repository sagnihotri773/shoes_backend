<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeVariantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        DB::table('variants')->truncate();
        $sizes = ['6', '7', '8', '9', '10'];

        foreach ($sizes as $size) {
            DB::table('variants')->insert([
                'size' => 'Size ' . $size,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
