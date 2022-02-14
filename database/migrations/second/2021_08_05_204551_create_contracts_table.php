<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('contract_category_id')->nullable()->constrained('contract_category')->onDelete('cascade');
            $table->foreignId('contractor_id')->constrained('contractors')->onDelete('cascade');
            $table->foreignId("unit_id")->constrained("units")->onDelete("cascade");
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string("name",500);
            $table->float("amount",30,2)->default(0);
            $table->string("contract_row",100)->nullable();
            $table->date("date_of_contract")->nullable();
            $table->date("contract_start_date")->nullable();
            $table->date("contract_completion_date")->nullable();
            $table->boolean("is_active")->default(1);
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
        Schema::dropIfExists('contracts');
    }
}
