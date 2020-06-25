<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateComputationVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('computation_variableables');
        Schema::table('computation_variables', function (Blueprint $table) {
            $table->enum('type', ['rating-sheets']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('computation_variables', function (Blueprint $table) {
            //
        });
    }
}
