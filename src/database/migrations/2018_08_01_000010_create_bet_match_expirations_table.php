<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetMatchExpirationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_match_expirations', function (Blueprint $table) {
            // Columns
            $table->string('bet_match_id')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx0_address')->index();
            $table->string('tx1_address')->index();
            $table->timestamp('confirmed_at')->index();
            $table->timestamps();
            // Indexes
            $table->primary('bet_match_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bet_match_expirations');
    }
}
