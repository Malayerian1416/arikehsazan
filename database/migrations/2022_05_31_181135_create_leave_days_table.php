<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId("daily_leave_id")->constrained("daily_leaves")->onDelete("cascade");
            $table->string("year",50);
            $table->string("month",50);
            $table->string("day",50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_days');
    }
}
