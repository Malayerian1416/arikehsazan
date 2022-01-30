<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerAutomationPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_automation_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("worker_payments_automation_id")->constrained("worker_payments_automation")->onDelete("cascade");
            $table->string("bank_name",500);
            $table->decimal("amount_payed",25,2);
            $table->string("deposit_kind_string",500);
            $table->string("deposit_kind_number",500);
            $table->string("payment_receipt_number",500);
            $table->string("payment_receipt_scan",500)->nullable();
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
        Schema::dropIfExists('worker_automation_payments');
    }
}
