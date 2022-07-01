<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHourlyLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hourly_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId("staff_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("location_id")->nullable()->constrained("Locations")->onDelete("cascade");
            $table->string("year",50);
            $table->string("month",50);
            $table->string("day",50);
            $table->string("departure",50)->nullable();
            $table->string("arrival",50)->nullable();
            $table->text("reason");
            $table->boolean("is_approved")->default(0);
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
        Schema::dropIfExists('hourly_leaves');
    }
}
