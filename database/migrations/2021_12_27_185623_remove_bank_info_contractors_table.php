<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBankInfoContractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->dropColumn("bank_name");
            $table->dropColumn("bank_card_number");
            $table->dropColumn("bank_account_number");
            $table->dropColumn("bank_sheba_number");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->string("bank_name",200)->nullable();
            $table->string("bank_card_number",50)->nullable();
            $table->string("bank_account_number",150)->nullable();
            $table->string("bank_sheba_number",150)->nullable();
        });
    }
}
