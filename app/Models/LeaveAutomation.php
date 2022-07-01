<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAutomation extends Model
{
    use HasFactory;
    protected $table = "leave_automation";
    protected $fillable = ["automationable_id","automationable_type","previous_role_id","current_role_id","next_role_id","is_read","is_finished"];

    public function automationable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeaveAutomationComment::class,"leave_automation_id");
    }
    public function signs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeaveAutomationSign::class,"leave_automation_id");
    }
}
