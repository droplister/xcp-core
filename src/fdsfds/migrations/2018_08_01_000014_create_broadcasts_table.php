<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broadcasts', function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('tx_index')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx_hash')->unique();
            $table->string('source')->index();
            $table->decimal('value', 25, 6)->nullable();
            $table->unsignedInteger('fee_fraction_int')->nullable();
            $table->mediumText('text')->nullable();
            $table->boolean('locked');
            $table->string('status');
            $table->unsignedBigInteger('timestamp');
            $table->timestamp('confirmed_at')->nullable()->index();
            $table->timestamps();
            // Indexes
            $table->primary('tx_index');
            $table->index(['status', 'source']);
            $table->index(['status', 'source', 'tx_index']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broadcasts');
    }
}
