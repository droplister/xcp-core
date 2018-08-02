<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replaces', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('address')->index();
            $table->unsignedInteger('options');
            $table->unsignedInteger('block_index')->index();
            $table->timestamp('confirmed_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replaces');
    }
}
