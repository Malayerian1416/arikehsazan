<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Contract extends Model
{
    use HasFactory;
    protected $table = "contracts";
    protected $fillable = ['project_id','contract_category_id','contractor_id','unit_id','user_id','name','amount','contract_row','date_of_contract','contract_start_date','contract_completion_date','is_active'];

    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class,"contract_id");
    }
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ContractCategory::class,"contract_category_id");
    }
    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class,"project_id");
    }
    public function contractor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contractor::class,"contractor_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Unit::class,"unit_id");
    }
    public function automation_amounts(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(InvoiceAutomationAmounts::class,Invoice::class);
    }
    public static function get_permissions(array $relations)
    {
        return self::query()->with($relations)->whereHas("project",function ($query){$query->whereHas("permitted_user",function ($query){$query->where("users.id","=",Auth::id());});})->orderBy("id")->get();
    }
    public function change_activation(){
        if($this->is_active == 0) {
            $this->update(["is_active" => 1]);
            return "active";
        }
        else if ($this->is_active == 1) {
            $this->update(["is_active" => 0]);
            return "deactive";
        }
        return false;
    }
}
