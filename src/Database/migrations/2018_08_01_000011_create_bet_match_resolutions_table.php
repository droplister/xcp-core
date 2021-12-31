<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetMatchResolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_match_resolutions', function (Blueprint $table) {
            // Columns
            $table->string('bet_match_id')->unique();
            $table->unsignedInteger('bet_match_type_id')->index();
            $table->unsignedInteger('block_index')->index();
            $table->string('winner')->nullable();
            $table->boolean('settled')->nullable();
            $table->unsignedBigInteger('bull_credit')->nullable();
            $table->unsignedBigInteger('bear_credit')->nullable();
            $table->unsignedBigInteger('escrow_less_fee')->nullable();
            $table->unsignedBigInteger('fee');
            $table->timestamp('confirmed_at')->nullable()->index();
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
        Schema::dropIfExists('bet_match_resolutions');
    }
}
