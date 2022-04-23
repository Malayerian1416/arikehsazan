<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = "attendances";
    protected $fillable = ["staff_id","user_id","location_id","year","month","day","time","timestamp","type"];

    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class,"location_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
    public function staff(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"staff_id");
    }
    public function function_report($year,$month,$user_id,$type)
    {
        return self::query()->where("year",$year)->where("month",$month)->where("type",$type)->with(["user","location"])->whereHas("user",function ($query) use ($user_id){
            $query->where("id",$user_id);
        })->get();
    }
}
