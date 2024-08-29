<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            'user_id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'total_price' => 2 * 700,
            'order_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('orders')->insert([
            'user_id' => 1,
            'product_id' => 2,
            'quantity' => 2,
            'total_price' => 10 * 20,
            'order_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
