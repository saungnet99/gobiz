<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('card_id');
            $table->string('user_id');
            $table->string('type')->default('business');
            $table->string('theme_id')->nullable();
            $table->string('card_lang')->default('EN');
            $table->string('cover')->nullable();
            $table->string('cover_type')->default('photo');
            $table->string('profile')->nullable();
            $table->string('card_url')->unique();
            $table->string('card_type');
            $table->string('title');
            $table->longText('sub_title');
            $table->longText('description')->nullable();
            $table->text('enquiry_email')->nullable();
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->text('password')->nullable();
            $table->timestamp('expiry_time')->nullable();
            $table->string('card_status')->default('activated');
            $table->string('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_cards');
    }
}
