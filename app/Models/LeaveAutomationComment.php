<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAutomationComment extends Model
{
    use HasFactory;
    protected $table = "leave_comments";
    protected $fillable = ["leave_automation_id","user_id","comment"];

    public function automation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LeaveAutomation::class,"leave_automation_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
}
