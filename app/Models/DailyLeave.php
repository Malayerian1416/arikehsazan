<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLeave extends Model
{
    use HasFactory;
    protected $table = "daily_leaves";
    protected $fillable = ["staff_id","user_id","reason","is_approved"];
    protected $with = ["days"];
    public function days(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeaveDay::class,"daily_leave_id");
    }
    public function automation(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(LeaveAutomation::class,"automationable");
    }
    public function staff(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"staff_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
    public static function check_duplicates($staff_id,$year,$month,$day): bool
    {
        $result = self::query()->whereHas("staff",function ($query) use ($staff_id){$query->where("staff_id","=",$staff_id);})
            ->whereHas("days",function ($query) use ($year,$month,$day){$query->where("year",$year)->where("month",$month)->where("day",$day);})->get();
        return $result->isEmpty();
    }
}
