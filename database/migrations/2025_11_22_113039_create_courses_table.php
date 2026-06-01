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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('course_code')->unique();
            $table->text('description')->nullable();
            $table->string('classroom');
            $table->enum('course_type', ['theory', 'lab']);
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('cascade');
            // The Instructor
            $table->foreignId('instructor_id')
            ->constrained('users', 'Academic_ID')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
