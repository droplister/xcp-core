<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuicidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suicides', function (Blueprint $table) {
            // Columns
            $table->string('contract_id')->unique();
            $table->timestamp('confirmed_at')->nullable()->index();
            $table->timestamps();
            // Indexes
            $table->primary('contract_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suicides');
    }
}
