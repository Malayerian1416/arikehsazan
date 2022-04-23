<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ["name","is_active","user_id"];
    protected $table = "roles";
    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class,"role_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
    public function menu_headers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MenuHeader::class,"role_menu_header","role_id","menu_header_id")->orderBy('menu_header_id');
    }
    public function menu_items(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class,"role_menu","role_id","menu_item_id")->orderBy('menu_item_id')->withPivot(["menu_action_id","route"]);
    }
    public function menu_actions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MenuAction::class,"role_menu_action","role_id","menu_action_id")->orderBy('menu_action_id')->withPivot(["menu_item_id","permission"]);
    }
    public function invoice_flow(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InvoiceFlow::class,"role_id");
    }
}
