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
        Schema::create('card_appointment_times', function (Blueprint $table) {
            $table->increments('id');
            $table->string('card_appointment_time_id')->uniqid();
            $table->string('card_id');
            $table->string('day');
            $table->integer('slot_duration')->default(30);
            $table->text('time_slots');
            $table->double('price', 15, 2)->default(0);
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
        Schema::dropIfExists('card_appointment_times');
    }
};
