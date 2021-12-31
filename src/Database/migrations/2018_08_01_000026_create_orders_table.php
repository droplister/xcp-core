<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('tx_index')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx_hash')->unique();
            $table->string('source')->index();
            $table->string('give_asset')->index();
            $table->unsignedBigInteger('give_quantity');
            $table->bigInteger('give_remaining');
            $table->string('get_asset')->index();
            $table->unsignedBigInteger('get_quantity');
            $table->bigInteger('get_remaining');
            $table->unsignedInteger('expiration');
            $table->unsignedInteger('expire_index')->index();
            $table->unsignedBigInteger('fee_required');
            $table->bigInteger('fee_required_remaining');
            $table->unsignedBigInteger('fee_provided');
            $table->bigInteger('fee_provided_remaining');
            $table->string('status')->index();
            $table->timestamp('confirmed_at')->nullable()->index();
            $table->timestamps();
            // Indexes
            $table->primary('tx_index');
            $table->index(['tx_index', 'tx_hash']);
            $table->index(['status', 'expire_index']);
            $table->index(['give_asset', 'status']);
            $table->index(['source', 'give_asset', 'status']);
            $table->index(['get_asset', 'give_asset', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
