<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkShiftIdUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("gender",50)->nullable();
            $table->foreignId("work_shift_id")->nullable()->constrained("work_shifts")->onDelete("cascade");
            $table->string("contract_number",100)->nullable();
            $table->decimal("daily_wage",20,2)->default(0);
            $table->decimal("overtime_rate",10,2)->default(0);
            $table->decimal("delay_rate",10,2)->default(0);
            $table->decimal("acceleration_rate",10,2)->default(0);
            $table->decimal("absence_rate",10,2)->default(0);
            $table->decimal("mission_rate",10,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("gender");
            $table->dropForeign("users_work_shift_id_foreign");
            $table->dropColumn("work_shift_id");
            $table->dropColumn("contract_number");
            $table->dropColumn("daily_wage");
            $table->dropColumn("overtime_rate");
            $table->dropColumn("delay_rate");
            $table->dropColumn("acceleration_rate");
            $table->dropColumn("absence_rate");
            $table->dropColumn("mission_rate");
        });
    }
}
