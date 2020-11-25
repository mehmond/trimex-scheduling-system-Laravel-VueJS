<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeysForPreferredTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preferred_times', function (Blueprint $table) {
            $table->foreign('time_id')->references('id')->on('times')->onDelete('cascade');
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preferred_times', function (Blueprint $table) {
            $table->dropForeign('preferred_times_time_id_foreign');
            $table->dropForeign('preferred_times_user_id_foreign');
            $table->dropForeign('preferred_times_day_id_foreign');
        });
    }
}
