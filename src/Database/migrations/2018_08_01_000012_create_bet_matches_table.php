<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_matches', function (Blueprint $table) {
            // Columns
            $table->string('id')->unique();
            $table->unsignedInteger('tx0_index')->index();
            $table->string('tx0_hash');
            $table->string('tx0_address')->index();
            $table->unsignedInteger('tx1_index')->index();
            $table->string('tx1_hash');
            $table->string('tx1_address')->index();
            $table->unsignedInteger('tx0_bet_type');
            $table->unsignedInteger('tx1_bet_type');
            $table->string('feed_address');
            $table->bigInteger('initial_value');
            $table->unsignedBigInteger('deadline');
            $table->decimal('target_value');
            $table->unsignedInteger('leverage');
            $table->unsignedBigInteger('forward_quantity');
            $table->unsignedBigInteger('backward_quantity');
            $table->unsignedInteger('tx0_block_index')->index();
            $table->unsignedInteger('tx1_block_index')->index();
            $table->unsignedInteger('block_index')->index();
            $table->unsignedInteger('tx0_expiration');
            $table->unsignedInteger('tx1_expiration');
            $table->unsignedInteger('match_expire_index');
            $table->unsignedInteger('fee_fraction_int');
            $table->string('status')->index();
            $table->timestamp('confirmed_at')->nullable()->index();
            $table->timestamps();
            // Indexes
            $table->primary('id');
            $table->index(['status', 'match_expire_index']);
            $table->index(['status', 'feed_address']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bet_matches');
    }
}
