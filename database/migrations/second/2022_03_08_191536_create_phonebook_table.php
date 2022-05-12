<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonebookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phonebook', function (Blueprint $table) {
            $table->id();
            $table->string("name",255);
            $table->string("job_title",255)->nullable();
            $table->string("phone_number_1",50)->nullable();
            $table->string("phone_number_2",50)->nullable();
            $table->string("phone_number_3",50)->nullable();
            $table->string("email",255)->nullable();
            $table->string("address",255)->nullable();
            $table->text("note")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phonebook');
    }
}
