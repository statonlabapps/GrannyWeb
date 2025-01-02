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
        Schema::create('analyses', function (Blueprint $table) {
            $table->id();
            $table->string('custom_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->foreignId('team_id')->nullable();
            $table->string('a1_model')->nullable();
            $table->string('a1_input')->nullable();
            $table->string('a2_type')->nullable();
            $table->string('a2_input')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('original_image')->nullable();
            $table->string('original_image_path')->nullable();
            $table->string('original_image_type')->nullable();
            $table->dateTime('segmentation_timestamp')->nullable();
            $table->string('rating_timestamp')->nullable();
            $table->integer('segmented_image_count')->nullable();
            $table->integer('rating_image_count')->nullable();
            $table->string('segmentation_output')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyses');
    }
};
