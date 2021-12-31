<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('tx_index')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->unsignedInteger('message_index')->unique()->nullable();
            $table->string('tx_hash')->unique();
            $table->string('type')->index();
            $table->string('source');
            $table->string('destination')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('fee')->default(0);
            $table->unsignedInteger('size')->default(0);
            $table->unsignedInteger('vsize')->default(0);
            $table->unsignedInteger('inputs')->default(0);
            $table->unsignedInteger('outputs')->default(0);
            $table->json('raw')->nullable();
            $table->boolean('valid');
            $table->unsignedBigInteger('timestamp');
            $table->timestamp('confirmed_at')->nullable()->index();
            $table->timestamp('processed_at')->nullable()->index();
            $table->timestamps();
            // Indexes
            $table->primary('tx_index');
            $table->index(['tx_index', 'tx_hash', 'block_index']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
