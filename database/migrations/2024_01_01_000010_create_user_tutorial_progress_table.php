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
        Schema::create('user_tutorial_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tutorial_step_id')->constrained()->onDelete('cascade');
            $table->boolean('completed')->default(false);
            $table->boolean('skipped')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'tutorial_step_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tutorial_progress');
    }
};