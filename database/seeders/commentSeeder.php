<?php

namespace Database\Seeders;

use id;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class commentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 1; $i <= 100; $i++) {
            $userId = Auth::guard('user')->id(); // Access the authenticated user's id

                DB::table('comments')->insert([
                    'comment_users' => $i,
                    'feedback_id' => $i,
                    'comments'    => 'this comments for data'
                ]);
        }
    }
}
