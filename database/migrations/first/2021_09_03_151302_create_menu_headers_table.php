<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_headers', function (Blueprint $table) {
            $table->id();
            $table->string("name",255);
            $table->string("slug",500);
            $table->foreignId("icon_id")->nullable()->constrained("icons");
            $table->boolean("is_admin")->default(0);
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
        Schema::dropIfExists('menu_headers');
    }
}
