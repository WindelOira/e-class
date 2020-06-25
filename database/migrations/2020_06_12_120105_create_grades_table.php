<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('grading_sheet_student');
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->integer('grading_sheet_id');
            $table->integer('student_id');
            $table->json('written_work');
            $table->json('performance_task');
            $table->json('quarterly_assessment');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grades');
    }
}
