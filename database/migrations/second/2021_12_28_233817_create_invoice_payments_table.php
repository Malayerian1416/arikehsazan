<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("invoice_id")->constrained("invoices")->onDelete("cascade");
            $table->string("bank_name",500);
            $table->decimal("amount_payed",25,2);
            $table->string("deposit_kind_string",500);
            $table->string("deposit_kind_number",500);
            $table->string("payment_receipt_number",500);
            $table->boolean("receipt_scan")->default(0);
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
        Schema::dropIfExists('invoice_payments');
    }
}
