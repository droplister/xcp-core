<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDispensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispenses', function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('tx_index')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx_hash')->unique();
            $table->unsignedInteger('dispense_index')->index();
            $table->string('source')->index();
            $table->string('destination')->index();
            $table->string('asset')->index();
            $table->unsignedBigInteger('dispense_quantity');
            $table->string('dispenser_tx_hash');
            // Indexes
            $table->primary('tx_index');
            $table->index(['tx_index', 'dispense_index', 'source', 'destination']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispenses');
    }
}
