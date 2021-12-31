<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('executions', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->unsignedInteger('tx_index')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx_hash')->unique();
            $table->string('source')->index();
            $table->string('contract_id');
            $table->unsignedBigInteger('gas_price');
            $table->unsignedBigInteger('gas_start');
            $table->unsignedBigInteger('gas_cost');
            $table->bigInteger('gas_remained');
            $table->unsignedBigInteger('value');
            $table->binary('data');
            $table->binary('output');
            $table->string('status');
            $table->timestamp('confirmed_at')->nullable()->index();
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
        Schema::dropIfExists('executions');
    }
}