<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractorBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractor_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("contractor_id")->constrained("contractors")->onDelete("cascade");
            $table->string("name",500);
            $table->string("card",500);
            $table->string("account",500);
            $table->string("sheba",500);
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
        Schema::dropIfExists('contractor_banks');
    }
}
