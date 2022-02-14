<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceFlowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_flow', function (Blueprint $table) {
            $table->id();
            $table->foreignId("role_id")->constrained("roles")->onDelete("cascade");
            $table->boolean("is_starter")->default(0);
            $table->boolean("is_finisher")->default(0);
            $table->boolean("is_main")->default(0);
            $table->boolean("quantity")->default(1);
            $table->boolean("amount")->default(1);
            $table->boolean("payment_offer")->default(1);
            $table->boolean("priority");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_flow');
    }
}
