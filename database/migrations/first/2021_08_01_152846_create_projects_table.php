<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string("name",500);
            $table->string("contract_row",500)->nullable();
            $table->string("control_system",500)->nullable();
            $table->string("executive_system",500)->nullable();
            $table->float("contract_amount",40);
            $table->string("date_of_contract",50);
            $table->string("project_start_date",50);
            $table->string("project_completion_date",50);
            $table->string("project_address",500)->nullable();
            $table->boolean("is_active")->default(1);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('projects');
    }
}
