<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HourlyLeave extends Model
{
    use HasFactory;
    protected $table = "hourly_leaves";
    protected $fillable = ["staff_id","user_id","location_id","reason","year","month","day","departure","arrival","is_approved","current_status","timestamp"];

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
    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class,"location_id");
    }
    public static function status(): array
    {
        $information["flag"] = false;
        $result = self::query()->where("current_status","=",1)
            ->where("year","=",verta()->format("Y"))->where("month","=",verta()->format("n"))->where("day","=",verta()->format("j"))->whereHas("automation")->whereHas("staff",function ($query){
                $query->where("id","=",Auth::id());
            })->first();
        if ($result){
            $information["flag"] = true;
            $information["leave"] = $result;
        }
        return $information;
    }
}
