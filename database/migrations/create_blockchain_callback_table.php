<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockchainCallbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blockchain_callbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference', 24);
            $table->string('key')->nullable();
            $table->string('address', 34)->index();
            $table->string('transaction_hash');
            $table->string('value');
            $table->unsignedInteger('confirmations')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blockchain_callbacks');
    }
}
