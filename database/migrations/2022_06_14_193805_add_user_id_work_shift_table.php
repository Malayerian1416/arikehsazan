<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdWorkShiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_shifts', function (Blueprint $table) {
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_shifts', function (Blueprint $table) {
            $table->dropForeign("work_shifts_user_id_foreign");
            $table->dropColumn("user_id");
        });
    }
}
