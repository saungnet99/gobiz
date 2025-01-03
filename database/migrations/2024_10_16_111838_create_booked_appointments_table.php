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
        Schema::create('booked_appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('booked_appointment_id')->uniqid();
            $table->string('card_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('notes')->nullable();
            $table->date('booking_date');
            $table->string('booking_time');
            $table->double('total_price', 15, 2)->default(0);
            $table->integer('booking_status')->default(0);
            $table->string('status')->default('1');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booked_appointments');
    }
};
