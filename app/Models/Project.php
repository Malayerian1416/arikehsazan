<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name','contract_row','control_system','executive_system','contract_amount','date_of_contract','project_start_date','project_completion_date','project_address','is_active','user_id'];
    protected $table = "projects";

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function permitted_user(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,"project_user","project_id","user_id");
    }
    public function contracts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contract::class,"project_id");
    }
    public function worker_automations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WorkerPaymentAutomation::class,"project_id");
    }
    public static function get_permissions(array $relations): Collection
    {
        return self::query()->with($relations)->whereHas("permitted_user",function ($query){$query->where("users.id","=",Auth::id());})->orderBy("id")->get();
    }
}
