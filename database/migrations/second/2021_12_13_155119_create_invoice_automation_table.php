<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceAutomationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_automation', function (Blueprint $table) {
            $table->id();
            $table->foreignId("invoice_id")->constrained("invoices")->onDelete("cascade");
            $table->bigInteger("previous_role_id");
            $table->bigInteger("current_role_id");
            $table->bigInteger("next_role_id");
            $table->boolean("is_read")->default(0);
            $table->boolean("is_finished")->default(0);
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
        Schema::dropIfExists('invoice_automation');
    }
}
