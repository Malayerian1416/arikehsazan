<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckPapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("check_id")->constrained("checks")->onDelete("cascade");
            $table->foreignId("doc_id")->constrained("docs")->onDelete("cascade");
            $table->unsignedBigInteger("check_number");
            $table->decimal("amount",25,0)->default(0);
            $table->date("receipt_date");
            $table->boolean("registered")->default(0);
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
        Schema::dropIfExists('check_papers');
    }
}
