<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPermissionsInvoiceFlowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_flow', function (Blueprint $table) {
            $table->boolean("quantity")->default(1);
            $table->boolean("amount")->default(1);
            $table->boolean("payment_offer")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_flow', function (Blueprint $table) {
            $table->dropColumn("quantity");
            $table->dropColumn("amount");
            $table->dropColumn("payment_offer");
        });
    }
}
