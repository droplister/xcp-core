<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debits', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->unsignedInteger('block_index')->index();
            $table->string('address')->index();
            $table->string('asset')->index();
            $table->unsignedBigInteger('quantity');
            $table->string('action');
            $table->string('event');
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
        Schema::dropIfExists('debits');
    }
}
