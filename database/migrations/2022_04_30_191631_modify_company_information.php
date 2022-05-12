<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCompanyInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_information', function (Blueprint $table) {
            $table->dropColumn("ceo");
            $table->foreignId("ceo_user_id")->nullable()->constrained("users")->onDelete("cascade");
            $table->string("app_ver",5);
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
        Schema::table('company_information', function (Blueprint $table) {
            $table->string("ceo",255);
            $table->dropForeign("company_information_ceo_user_id_foreign")->nullable()->constrained("roles")->onDelete("cascade");
            $table->dropColumn("ceo_user_id");
            $table->dropColumn("app_ver");
        });
    }
}
