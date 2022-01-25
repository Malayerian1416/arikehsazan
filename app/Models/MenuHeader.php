<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuHeader extends Model
{
    use HasFactory;
    protected $fillable = ["name","slug","icon_id","is_admin"];
    protected $table = "menu_headers";

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class,"role_menu_header","menu_header_id","role_id");
    }
    public function menu_titles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MenuTitle::class,"menu_header_id");
    }
    public function menu_items(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(MenuItem::class,MenuTitle::class,"menu_header_id","menu_title_id");
    }
    public function icon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Icon::class,"icon_id");
    }
}