<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToContractCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_category', function (Blueprint $table) {
            $table->foreignId("contract_branch_id")->constrained("contract_branches")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_category', function (Blueprint $table) {
            $table->dropForeign("contract_category_contract_branch_id_foreign");
            $table->dropColumn("contract_branch_id");
        });
    }
}
