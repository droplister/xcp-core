<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMempoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mempool', function (Blueprint $table) {
            // Columns
            $table->string('tx_hash');
            $table->string('command');
            $table->string('category');
            $table->json('bindings');
            $table->unsignedBigInteger('timestamp');
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
        Schema::dropIfExists('mempool');
    }
}
