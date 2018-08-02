<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpsresolvesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpsresolves', function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('tx_index')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx_hash')->unique();
            $table->string('source')->index();
            $table->unsignedInteger('move');
            $table->string('random');
            $table->string('rps_match_id')->index();
            $table->string('status');
            $table->timestamp('confirmed_at')->index();
            $table->timestamps();
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
        Schema::dropIfExists('rpsresolves');
    }
}
