<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feedback_users')->nullable();
            $table->foreign('feedback_users')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->string('feedback_title');
            $table->unsignedBigInteger('feedback_category')->nullable();
            $table->foreign('feedback_category')->references('id')->on('categorys')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->longText('feedback_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
