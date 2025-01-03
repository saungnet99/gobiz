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
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id')->uniqid();
            $table->string('coupon_id');
            $table->string('coupon_code');
            $table->string('coupon_desc')->nullable();
            $table->string('coupon_type');
            $table->double('coupon_amount');
            $table->datetime('coupon_expired_on');
            $table->integer('coupon_user_usage_limit');
            $table->integer('coupon_total_usage_limit');
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
