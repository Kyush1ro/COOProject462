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
        Schema::create('announcement_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->onDelete('cascade');
            
            // Fix: Reference 'Academic_ID' instead of default 'id'
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('Academic_ID')->on('users')->onDelete('cascade');
            
            $table->timestamps();

            $table->unique(['announcement_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_views');
    }
};
