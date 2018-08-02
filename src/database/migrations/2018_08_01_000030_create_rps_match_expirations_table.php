<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpsMatchExpirationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rps_match_expirations', function (Blueprint $table) {
            // Columns
            $table->string('rps_match_id')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx0_address')->index();
            $table->string('tx1_address')->index();
            $table->timestamp('confirmed_at')->index();
            $table->timestamps();
            // Indexes
            $table->primary('rps_match_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rps_match_expirations');
    }
}
