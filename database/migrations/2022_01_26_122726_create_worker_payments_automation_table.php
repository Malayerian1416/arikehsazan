<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerPaymentsAutomationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_payments_automation', function (Blueprint $table) {
            $table->id();
            $table->foreignId("project_id")->constrained("projects")->onDelete("cascade");
            $table->foreignId("contractor_id")->constrained("contractors")->onDelete("cascade");
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->unsignedBigInteger("previous_role_id");
            $table->unsignedBigInteger("current_role_id");
            $table->unsignedBigInteger("next_role_id");
            $table->decimal("amount",20,0)->default(0);
            $table->text("description")->nullable();
            $table->boolean("is_read")->default(0);
            $table->boolean("is_finished")->default(0);
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
        Schema::dropIfExists('worker_payments_automation');
    }
}
