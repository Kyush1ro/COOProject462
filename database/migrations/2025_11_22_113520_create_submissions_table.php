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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');

            // CHANGE HERE: Link to Academic_ID
            $table->foreignId('student_id')
                ->constrained('users', 'Academic_ID')
                ->onDelete('cascade');
            $table->string('file_path');
            $table->decimal('grade', 5, 2)->nullable(); // Allows numbers like 95.50

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
