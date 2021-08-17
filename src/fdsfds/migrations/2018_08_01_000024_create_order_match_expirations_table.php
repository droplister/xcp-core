<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMatchExpirationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_match_expirations', function (Blueprint $table) {
            // Columns
            $table->string('order_match_id')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx0_address')->index();
            $table->string('tx1_address')->index();
            $table->timestamp('confirmed_at')->nullable()->index();
            $table->timestamps();
            // Indexes
            $table->primary('order_match_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_match_expirations');
    }
}
