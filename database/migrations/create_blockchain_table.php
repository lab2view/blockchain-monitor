<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockchainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blockchain_xpubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string("label", 111)->unique();
            $table->unsignedInteger('gab')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blockchain_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('xpub_id');
            $table->string('label', 34)->index();
            $table->unsignedInteger('index')->default(0);
            $table->string('callback');
            $table->string('reference', 24);
            $table->string('amount')->nullable();
            $table->boolean('is_busy')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('xpub_id')->references('id')->on('blockchain_xpubs');
        });

        Schema::create('blockchain_invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('address_id');
            $table->string('reference', 24)->nullable();
            $table->string('request_amount', 16);
            $table->string('response_amount', 16)->nullable();
            $table->string('state', 10);
            $table->string('hash')->nullable();
            $table->unsignedInteger('confirmations')->nullable();
            $table->text('custom_data')->nullable();
            $table->timestamps();

            $table->foreign('address_id')->references('id')->on('blockchain_addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blockchain_invoices');
        Schema::dropIfExists('blockchain_addresses');
        Schema::dropIfExists('blockchain_xpubs');
    }
}
