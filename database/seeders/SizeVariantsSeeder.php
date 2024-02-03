<?php

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
        $sizes = ['6', '7', '8', '9', '10'];

        foreach ($sizes as $size) {
            DB::table('variants')->insert([
                'name' => 'Size ' . $size,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
