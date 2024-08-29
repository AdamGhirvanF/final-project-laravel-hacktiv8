<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'name' => 'Smartphone',
            'category_id' => 1,
            'price' => 700,
        ]);

        DB::table('products')->insert([
            'name' => 'Chicken',
            'category_id' => 2,
            'price' => 20,
        ]);

        DB::table('products')->insert([
            'name' => 'Red Meat',
            'category_id' => 2,
            'price' => 40,
        ]);
    }
}
