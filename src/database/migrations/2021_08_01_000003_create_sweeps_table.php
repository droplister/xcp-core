<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSweepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sweeps', function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('tx_index')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx_hash')->unique();
            $table->string('source')->index();
            $table->string('destination')->index();
            $table->unsignedInteger('flags');
            $table->string('status')->index();
            $table->string('memo')->nullable();
            $table->unsignedBigInteger('fee_paid');

            // Indexes
            $table->primary('tx_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sweeps');
    }
}
