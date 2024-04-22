<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\feedback;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Auth;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        // Create 100 fake feedbacks
        for ($i = 1; $i <= 100; $i++) {
            $userId = Auth::guard('user')->id(); // Access the authenticated user's id

                DB::table('feedbacks')->insert([
                    'feedback_users' => $i,
                    'feedback_title' => "Feedback Title $i",
                    'feedback_category' => 1,
                    'feedback_details' => "Details for feedback $i.",
                ]);
        }
    }
}
