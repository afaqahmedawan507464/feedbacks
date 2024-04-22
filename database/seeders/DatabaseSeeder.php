<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\category;
use App\Models\comment;
use App\Models\feedback;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // User::truncate();
        // category::truncate();
        // feedback::truncate();
        User::factory(100)->create();
        $this->call([
            Userseeder::class,
            categorySeeder::class,
            FeedbackSeeder::class,
            commentSeeder::class,
        ]);
    }
}
