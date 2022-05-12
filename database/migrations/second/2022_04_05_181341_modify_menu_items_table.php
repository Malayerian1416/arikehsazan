<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign("menu_items_menu_title_id_foreign");
            $table->dropColumn("menu_title_id");
            $table->foreignId("parent_id")->nullable()->constrained("menu_items")->onDelete("cascade");
            $table->foreignId("menu_header_id")->constrained("menu_headers")->onDelete("cascade");
            $table->string("route",255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreignId("menu_title_id")->constrained("menu_titles")->onDelete("cascade");
            $table->dropForeign("menu_items_parent_id_foreign");
            $table->dropForeign("menu_items_menu_header_id_foreign");
            $table->dropColumn("parent_id");
            $table->dropColumn("menu_header_id");
            $table->string("route",255);
        });
    }
}
