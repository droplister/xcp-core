<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpsMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rps_matches', function (Blueprint $table) {
            // Columns
            $table->string('id')->unique();
            $table->unsignedInteger('tx0_index')->index();
            $table->string('tx0_hash');
            $table->string('tx0_address')->index();
            $table->unsignedInteger('tx1_index')->index();
            $table->string('tx1_hash');
            $table->string('tx1_address')->index();
            $table->string('tx0_move_random_hash');
            $table->string('tx1_move_random_hash');
            $table->unsignedBigInteger('wager');
            $table->unsignedInteger('possible_moves');
            $table->unsignedInteger('tx0_block_index')->index();
            $table->unsignedInteger('tx1_block_index')->index();
            $table->unsignedInteger('block_index')->index();
            $table->unsignedInteger('tx0_expiration');
            $table->unsignedInteger('tx1_expiration');
            $table->unsignedInteger('match_expire_index')->index();
            $table->string('status')->index();
            $table->timestamp('confirmed_at')->index();
            $table->timestamps();
            // Indexes
            $table->primary('id');
            $table->index(['status', 'match_expire_index']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rps_matches');
    }
}
