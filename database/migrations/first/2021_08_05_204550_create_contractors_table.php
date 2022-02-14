<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer("type");
            $table->string("name",500);
            $table->string("birth_date",100)->nullable();
            $table->string("father_name",150)->nullable();
            $table->string("national_code",50)->nullable();
            $table->string("identify_number",50)->nullable();
            $table->string("tel",20)->nullable();
            $table->string("cellphone",20)->nullable();
            $table->string("address",500)->nullable();
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
        Schema::dropIfExists('contractors');
    }
}
