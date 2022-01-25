<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuAction extends Model
{
    use HasFactory;
    protected $table = "menu_actions";
    protected $fillable = ["name","action"];

    public function items(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class,"item_action","menu_action_id","menu_item_id");
    }
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class,"role_menu_action","menu_action_id","role_id");
    }
}
