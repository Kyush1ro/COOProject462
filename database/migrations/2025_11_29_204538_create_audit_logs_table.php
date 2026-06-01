<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Who did the action (links to User's Academic_ID)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('Academic_ID')->on('users')->onDelete('cascade');

            $table->string('action'); // e.g., 'created', 'updated', 'deleted'
            $table->string('model_type'); // e.g., 'App\Models\Course', 'App\Models\User'
            $table->unsignedBigInteger('model_id')->nullable(); // The ID of the item affected
            $table->json('old_values')->nullable(); // Data before change
            $table->json('new_values')->nullable(); // Data after change
            $table->ipAddress('ip_address')->nullable(); // User's IP address

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
