<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRollbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rollbacks', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->unsignedInteger('message_index')->index();
            $table->unsignedInteger('block_index')->index();
            $table->timestamp('processed_at')->nullable()->index();
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
        Schema::dropIfExists('rollbacks');
    }
}
