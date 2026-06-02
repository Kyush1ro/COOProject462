<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('spare_part_id')
                ->nullable()
                ->constrained('spare_parts')
                ->nullOnDelete();

            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('pr_number')->unique();
            $table->string('item_name');
            $table->integer('quantity');
            $table->string('unit')->default('pcs');

            $table->string('status')->default('pending');
            // pending, approved, rejected, ordered, received

            $table->text('justification')->nullable();
            $table->date('required_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_requisitions');
    }
};