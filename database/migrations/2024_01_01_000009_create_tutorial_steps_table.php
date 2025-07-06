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
        Schema::create('tutorial_steps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('target_element')->nullable(); // CSS selector for highlighting
            $table->integer('order');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_skippable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutorial_steps');
    }
};