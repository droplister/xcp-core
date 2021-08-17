<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBTCPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('btcpays', function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('tx_index')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx_hash')->unique();
            $table->string('source')->index();
            $table->string('destination')->index();
            $table->unsignedBigInteger('btc_amount');
            $table->string('order_match_id');
            $table->string('status');
            $table->timestamp('confirmed_at')->nullable()->index();
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
        Schema::dropIfExists('btcpays');
    }
}
