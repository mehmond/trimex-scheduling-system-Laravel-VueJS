<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeysForSectionSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section_schedules', function (Blueprint $table) {
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');
            $table->foreign('time_id')->references('id')->on('times')->onDelete('cascade');
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section_schedules', function (Blueprint $table){
            $table->dropForeign('section_schedules_section_id_foreign');
            $table->dropForeign('section_schedules_subject_id_foreign');
            $table->dropForeign('section_schedules_course_id_foreign');
            $table->dropForeign('section_schedules_year_id_foreign');
            $table->dropForeign('section_schedules_time_id_foreign');
            $table->dropForeign('section_schedules_day_id_foreign');
        });
    }
}
