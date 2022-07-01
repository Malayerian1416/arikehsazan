<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveFlowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_flow', function (Blueprint $table) {
            $table->id();
            $table->foreignId("role_id")->constrained("roles")->onDelete("cascade");
            $table->boolean("priority");
            $table->boolean("is_main")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_flow');
    }
}
