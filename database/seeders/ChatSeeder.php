<?php

namespace Database\Seeders;

use Database\Factories\ChatFactory;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChatFactory::new()->count(100)->create();
    }
}
