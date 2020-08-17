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
        Schema::create('xpubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string("label", 111)->unique();
            $table->unsignedInteger('gab')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('xpub_id');
            $table->string('label', 34)->index();
            $table->unsignedInteger('index')->default(0);
            $table->string('callback');
            $table->string('reference', 24);
            $table->string('amount')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('xpub_id')->references('id')->on('xpubs');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('address_id');
            $table->string('amount');
            $table->string('hash');
            $table->unsignedInteger('confirmations')->default(0);
            $table->string('state', 10);
            $table->timestamps();

            $table->foreign('address_id')->references('id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xpubs');
        Schema::dropIfExists('addresses');
    }
}
