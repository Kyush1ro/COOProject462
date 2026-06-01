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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            // Fix: 'Academic_ID' in users table is created via $table->id('Academic_ID'), which makes it an unsignedBigInteger.
            // So we must use unsignedBigInteger here, not string.
            $table->unsignedBigInteger('student_id'); 
            $table->foreign('student_id')->references('Academic_ID')->on('users')->onDelete('cascade');
            
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate attendance for same student/course/date
            $table->unique(['course_id', 'student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
