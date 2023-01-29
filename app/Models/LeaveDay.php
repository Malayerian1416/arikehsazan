<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveDay extends Model
{
    use HasFactory;
    protected $table = "leave_days";
    protected $fillable = ["daily_leave_id","year","month","day","timestamp"];

    public function daily_leave(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DailyLeave::class,"daily_leave_id");
    }
}
