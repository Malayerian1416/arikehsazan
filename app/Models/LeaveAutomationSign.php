<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAutomationSign extends Model
{
    use HasFactory;
    protected $table = "leave_signs";
    protected $fillable = ["leave_automation_id","user_id","sign"];

    public function automation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LeaveAutomation::class,"leave_automation_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
}
