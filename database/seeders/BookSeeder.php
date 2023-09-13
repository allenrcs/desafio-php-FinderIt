<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('books')->insert([
            [
                'title' => fake()->text(20),
                'description' => fake()->text(),
                'credit' => 1000,
                'image' => '1694616074.jpg',
                'user_id' => 1,
            ],
            [
                'title' => fake()->text(20),
                'description' => fake()->text(),
                'credit' => 1000,
                'image' => '1694616076.jpg',
                'user_id' => 1,
            ],
            [
                'title' => fake()->text(20),
                'description' => fake()->text(),
                'credit' => 1000,
                'image' => '1694616077.jpg',
                'user_id' => 1,
            ],
            [
                'title' => fake()->text(20),
                'description' => fake()->text(),
                'credit' => 1000,
                'image' => '1694616078.jpg',
                'user_id' => 1,
            ]

        ]);
        
    }
}
