<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string("name",500);
            $table->string("short_name",255)->nullable();
            $table->foreignId("menu_title_id")->constrained("menu_titles")->onDelete("cascade");
            $table->string("main_route",255);
            $table->string("icon",255)->nullable();
            $table->boolean("notifiable")->default(0);
            $table->string("notification_channel",255)->nullable();
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
        Schema::dropIfExists('menu_items');
    }
}
