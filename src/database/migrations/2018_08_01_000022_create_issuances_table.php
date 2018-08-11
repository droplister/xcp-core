<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuances', function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('tx_index')->unique();
            $table->unsignedInteger('block_index')->index();
            $table->string('tx_hash')->unique();
            $table->string('asset');
            $table->unsignedBigInteger('quantity');
            $table->decimal('quantity_normalized', 27, 8)->default(0);
            $table->boolean('divisible');
            $table->string('source')->index();
            $table->string('issuer');
            $table->boolean('transfer');
            $table->boolean('callable');
            $table->unsignedBigInteger('call_date');
            $table->decimal('call_price', 11, 2);
            $table->text('description');
            $table->unsignedBigInteger('fee_paid');
            $table->boolean('locked');
            $table->string('status')->index();
            $table->string('asset_longname')->nullable();
            $table->timestamp('confirmed_at')->index();
            $table->timestamps();
            // Indexes
            $table->primary('tx_index');
            $table->index(['asset', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issuances');
    }
}
