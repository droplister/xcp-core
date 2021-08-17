<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bets', function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('tx_index')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx_hash')->unique();
            $table->string('source')->index();
            $table->string('feed_address');
            $table->unsignedInteger('bet_type');
            $table->unsignedBigInteger('deadline');
            $table->unsignedBigInteger('wager_quantity');
            $table->bigInteger('wager_remaining');
            $table->unsignedBigInteger('counterwager_quantity');
            $table->bigInteger('counterwager_remaining');
            $table->decimal('target_value');
            $table->unsignedInteger('leverage');
            $table->unsignedInteger('expiration');
            $table->unsignedInteger('expire_index');
            $table->unsignedInteger('fee_fraction_int');
            $table->string('status');
            $table->timestamp('confirmed_at')->nullable()->index();
            $table->timestamps();
            // Indexes
            $table->primary('tx_index');
            $table->index(['tx_index', 'tx_hash']);
            $table->index(['status', 'expire_index']);
            $table->index(['feed_address', 'status', 'bet_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bets');
    }
}
