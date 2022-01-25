<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\Types\Self_;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'mobile',
        'role_id',
        'name',
        'email',
        'password',
        'user_id',
        'is_active',
        'sign'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * @var mixed
     */

    public function project(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Project::class,"user_id");
    }
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class,"role_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo($this,"user_id");
    }
    public function permitted_project(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Project::class,"project_user","user_id","project_id");
    }
    public function hasPermission($action,$model): bool
    {
        return in_array("{$model}.{$action}",$this->role->menu_items()->pluck("role_menu.route")->toArray());
    }
    public function isAdmin(): bool
    {
        return (bool)$this->is_admin;
    }
    public function activation(){
        if ($this->is_active == 1)
            $this->update(["is_active" => 0]);
        else
            $this->update(["is_active" => 1]);
        return $this->is_active;
    }
}
