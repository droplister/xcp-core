<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_matches', function (Blueprint $table) {
            // Columns
            $table->string('id')->unique();
            $table->unsignedInteger('tx0_index')->index();
            $table->string('tx0_hash');
            $table->string('tx0_address')->index();
            $table->unsignedInteger('tx1_index')->index();
            $table->string('tx1_hash');
            $table->string('tx1_address')->index();
            $table->string('forward_asset');
            $table->unsignedBigInteger('forward_quantity');
            $table->string('backward_asset');
            $table->unsignedBigInteger('backward_quantity');
            $table->unsignedInteger('tx0_block_index');
            $table->unsignedInteger('tx1_block_index');
            $table->unsignedInteger('block_index')->index();
            $table->unsignedInteger('tx0_expiration');
            $table->unsignedInteger('tx1_expiration');
            $table->unsignedInteger('match_expire_index');
            $table->bigInteger('fee_paid');
            $table->string('status')->index();
            $table->timestamp('confirmed_at')->nullable()->index();
            $table->timestamps();
            // Indexes
            $table->primary('id');
            $table->index(['status', 'match_expire_index']);
            $table->index(['forward_asset', 'status']);
            $table->index(['backward_asset', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_matches');
    }
}
