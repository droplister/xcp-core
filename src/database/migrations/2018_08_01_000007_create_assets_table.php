<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('block_index')->index();
            $table->unsignedInteger('tx_index')->default(0)->index();
            $table->string('asset_name');
            $table->string('asset_longname')->nullable();
            $table->string('type')->index();
            $table->string('owner')->nullable()->index();
            $table->string('issuer')->nullable()->index();
            $table->text('description')->nullable();
            $table->json('meta')->nullable();
            $table->unsignedBigInteger('issuance')->default(0);
            $table->boolean('divisible')->default(0);
            $table->boolean('locked')->default(0);
            $table->timestamp('confirmed_at')->index();
            $table->timestamps();
            // Indexes
            $table->primary('asset_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
}
