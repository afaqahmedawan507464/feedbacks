<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class categorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorys')->insertOrIgnore([
            'category_name' => 'bug Reports',
            // Add other attributes as needed
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('categorys')->insertOrIgnore([
            'category_name' => 'suggistions',
            // Add other attributes as needed
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
