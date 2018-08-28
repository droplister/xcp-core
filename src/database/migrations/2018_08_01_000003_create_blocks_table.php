<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            // Columns
            $table->unsignedBigInteger('block_index');
            $table->string('block_hash')->unique();
            $table->string('ledger_hash')->unique();
            $table->string('txlist_hash')->unique();
            $table->string('messages_hash')->unique();
            $table->string('previous_block_hash')->unique();
            $table->string('next_block_hash')->unique()->nullable();
            $table->string('merkle_root')->nullable();
            $table->string('chainwork')->nullable();
            $table->unsignedBigInteger('difficulty');
            $table->unsignedBigInteger('nonce')->default(0);
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedBigInteger('stripped_size')->default(0);
            $table->unsignedBigInteger('weight')->default(0);
            $table->unsignedBigInteger('tx_count')->default(0);
            $table->unsignedBigInteger('timestamp');
            $table->timestamp('confirmed_at')->nullable()->index();
            $table->timestamp('processed_at')->nullable()->index();
            $table->timestamps();
            // Indexes
            $table->primary('block_index');
            $table->index(['block_index', 'block_hash']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blocks');
    }
}
