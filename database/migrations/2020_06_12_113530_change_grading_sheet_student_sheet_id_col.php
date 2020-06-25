<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeGradingSheetStudentSheetIdCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grading_sheet_student', function (Blueprint $table) {
            $table->dropColumn('sheet_id');
            $table->integer('grading_sheet_id')->first();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grading_sheet_student', function (Blueprint $table) {
            //
        });
    }
}
