<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Swift;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//         Swift::factory()->count(1000)->create();
        Swift::factory()->count(10000)->create();
        Swift::factory()->count(10000)->create();
        Swift::factory()->count(10000)->create();
        Swift::factory()->count(10000)->create();
        Swift::factory()->count(10000)->create();
        Swift::factory()->count(10000)->create();
        Swift::factory()->count(10000)->create();
        Swift::factory()->count(10000)->create();
        Swift::factory()->count(10000)->create();
    }
}
