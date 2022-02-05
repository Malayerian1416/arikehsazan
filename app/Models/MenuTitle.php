<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuTitle extends Model
{
    use HasFactory;
    protected $table = "menu_titles";
    protected $fillable = ["name","menu_header_id","route","main_route","icon"];

    public function menu_header(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MenuHeader::class,"menu_header_id");
    }
    public function menu_items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MenuItem::class,"menu_title_id");
    }
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class,"role_menu_title","menu_title_id","role_id");
    }
}
