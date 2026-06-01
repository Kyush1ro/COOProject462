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
        Schema::create('progress', function (Blueprint $table) {
$table->id();
$table->foreignId('student_id')->constrained('users', 'Academic_ID')->onDelete('cascade');
$table->foreignId('course_id')->constrained()->onDelete('cascade');
$table->unsignedTinyInteger('percentage')->default(0); // 0–100
$table->timestamp('last_access_at')->nullable();
$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
