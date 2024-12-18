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
        Schema::create('vehicle_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');; // Employee who made the reservation
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');; // Vehicle being reserved
            $table->date('start_date'); // Reservation start date
            $table->date('end_date');   // Reservation end date
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Overall reservation status
            $table->enum('return_status', ['pending', 'returned'])->default('pending'); // Return status
            $table->unsignedBigInteger('approver_1_id'); // First level approver (flexible choice)
            $table->unsignedBigInteger('approver_2_id'); // Second level approver (flexible choice)
            $table->enum('approver_1_status', ['pending', 'approved', 'rejected'])->default('pending'); // First approver status
            $table->enum('approver_2_status', ['pending', 'approved', 'rejected'])->default('pending'); // Second approver status
            $table->foreignId('mine_id')->nullable()->constrained('mines')->onDelete('cascade'); // Related mine
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('cascade'); // Related office
            $table->timestamps();

            // Optional: Tambahkan relasi dengan tabel users jika tetap ingin memverifikasi ID approver
            $table->foreign('approver_1_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approver_2_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_reservation');
    }
};