<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("birth_date",100)->nullable();
            $table->string("father_name",150)->nullable();
            $table->string("national_code",50)->nullable();
            $table->string("identify_number",50)->nullable();
            $table->string("address",500)->nullable();
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
            $table->dropColumn("birth_date",100);
            $table->dropColumn("father_name",150);
            $table->dropColumn("national_code",50);
            $table->dropColumn("identify_number",50);
            $table->dropColumn("address",500);
        });
    }
}
