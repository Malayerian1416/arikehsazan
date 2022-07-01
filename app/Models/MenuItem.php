<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $table = "menu_items";
    protected $fillable = ["menu_header_id","parent_id","name","short_name","icon","main_route","route","notifiable","notification_channel","priority"];

    public function menu_title(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MenuTitle::class,"menu_title_id");
    }
    public function menu_header(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MenuHeader::class,"menu_header_id");
    }
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MenuItem::class,"parent_id");
    }
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MenuItem::class,"parent_id");
    }
    public function actions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MenuAction::class,"item_action","menu_item_id","menu_action_id");
    }
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class,"role_menu","menu_item_id","role_id");
    }
}
