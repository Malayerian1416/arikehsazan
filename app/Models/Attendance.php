<?php

namespace App\Models;

use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

function payable_time($duration): float
{
    $payable_time = explode(":",$duration);
    if (count($payable_time) == 2) {
        $hour = intval($payable_time[0]) > 0 ? $payable_time[0] : 0;
        $minute = intval($payable_time[1]) > 0 ? $payable_time[1] / 60 : 0;
        return round($hour + $minute, 2);
    }
    return 0;
}

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
    public static function get_working_days($id,$from,$to,$shift_id,$holidays): array
    {
        $jalali_from = verta($from);
        $jalali_to = verta($to);
        $days_of_week = [0=>"شنبه",1=>"یکشنبه",2=>"دوشنبه",3=>"سه شنبه",4=>"چهارشنبه",5=>"پنجشنبه",6=>"جمعه"];
        $staff = User::query()->findOrFail($id);
        $shift = WorkShift::query()->findOrFail($shift_id);
        $working_days = $staff->attendances()->with("location")->orderBy("day")->orderBy("time")->orderBy("month")->orderBy("year")->where("timestamp",">=",$from)->where("timestamp","<",$to)->get();
        $daily_leaves = $staff->leave_days()->where("timestamp",">=",$from)->where("timestamp","<",$to)->get();
        $hourly_leaves = $staff->hourly_leaves()->where("timestamp",">=",$from)->where("timestamp","<",$to)->get();
        $result = [];
        $date_diff = $jalali_from->diffDays($jalali_to);
        $payable_work_time = payable_time($shift->duration);
        $hourly_wage = ceil($staff->daily_wage / $payable_work_time);
        $delay_rate = $staff->delay_rate * $hourly_wage;
        $acceleration_rate = $staff->acceleration_rate * $hourly_wage;
        $absence_rate = $staff->absence_rate * $hourly_wage;
        $overtime_rate = $staff->overtime_rate * $hourly_wage;
        for($i = 0;$i <= $date_diff - 1;$i++){
            $holiday = $jalali_from->isFriday() || in_array($jalali_from->format("Y/m/d"),$holidays);
            $is_in_working_day = $working_days->where("year","=",intval($jalali_from->format("Y")))->where("month","=",intval($jalali_from->format("n")))->where("day","=",intval($jalali_from->format("d")))->first();
            $date = $jalali_from->format("Y/m/d");
            $day_of_week = $days_of_week[$jalali_from->dayOfWeek];
            $status = 0;
            $err_message = "";
            $attendances = [];
            $total_work_duration = 0;
            $operation = 0;
            $total_hourly_leave_duration = 0;
            $delay = 0;
            $acceleration = 0;
            $total_overtime_work_duration = 0;
            $total_free_overtime_work_duration = 0;
            $total_absence_duration = 0;
            $total_leave_days = 0;
            $color = "#e9e9e9";
            $attendance = "حاضر";
            $location = '';
            if ($is_in_working_day){
                $is_in_daily_leaves = $daily_leaves
                    ->where("year", "=", intval($jalali_from->format("Y")))
                    ->where("month", "=", intval($jalali_from->format("n")))
                    ->where("day", "=", intval($jalali_from->format("j")))->first();
                $day_attendances = $working_days->where("year","=",intval($jalali_from->format("Y")))->where("month","=",intval($jalali_from->format("n")))->where("day","=",intval($jalali_from->format("d")));
                $presence_count = $day_attendances->where("type","=","presence")->count();
                $absence_count = $day_attendances->where("type","=","absence")->count();
                $sample = $working_days->where("year","=",intval($jalali_from->format("Y")))->where("month","=",intval($jalali_from->format("n")))->where("day","=",intval($jalali_from->format("d")))->where("type","=","presence")->first();
                if ($sample != null && $sample->location != null)
                    $location = $sample->location->name;
                if ($presence_count == $absence_count) {
                    foreach ($day_attendances as $day)
                        $attendances[] = ["type" => $day->type, "time" => $day->time];
                    foreach (array_chunk($attendances, 2) as $group) {
                        $presence = 0;$absence = 0;
                        foreach ($group as $item) {
                            $item["type"] == "presence" ? $presence = $item["time"]:$absence = $item["time"];
                        }
                        $total_work_duration += strtotime($absence) - strtotime($presence);
                    }
                    if ($holiday){
                        $total_free_overtime_work_duration = $total_work_duration;
                        $operation = $total_work_duration = strtotime($shift->departure) - strtotime($shift->arrival);
                        $operation = gmdate("H:i",$operation);
                        $attendance = "بدون شیفت";
                    }
                    elseif ($is_in_daily_leaves && $is_in_daily_leaves->daily_leave->is_approved == 1){
                        $total_free_overtime_work_duration = $total_work_duration;
                        $operation = $total_work_duration = strtotime($shift->departure) - strtotime($shift->arrival);
                        $operation = gmdate("H:i",$operation);
                        $color = "#D3FFB5";
                        $attendance = "مرخصی";
                        $total_leave_days++;
                    }
                    else {
                        $color = "#ffffff";
                        $first_presence = $day_attendances->where("type", "=", "presence")->min("time");
                        $last_presence = $day_attendances->where("type", "=", "presence")->max("time");
                        if (strtotime($last_presence) > strtotime($shift->departure)) {
                            $last_absence = $day_attendances->where("type", "=", "absence")->where("time", "<" , $last_presence)->max("time");
                            $out_of_time_absence = $day_attendances->where("type", "=", "absence")->max("time");
                            $total_overtime_work_duration += strtotime($last_presence) > strtotime($shift->departure) ? strtotime($out_of_time_absence) - strtotime($last_presence) : 0;
                        }
                        else
                            $last_absence = $day_attendances->where("type", "=", "absence")->max("time");
                        $delay = strtotime($shift->arrival) <= strtotime($first_presence) ? gmdate("H:i", strtotime($first_presence) - strtotime($shift->arrival)) : 0;
                        $acceleration = strtotime($shift->departure) >= strtotime($last_absence) ? gmdate("H:i", strtotime($shift->departure) - strtotime($last_absence)) : 0;
                        $total_overtime_work_duration += strtotime($shift->arrival) > strtotime($first_presence) ? strtotime($shift->arrival) - strtotime($first_presence) : 0;
                        $total_overtime_work_duration += strtotime($shift->departure) < strtotime($last_absence) ? strtotime($last_absence) - strtotime($shift->departure) : 0;
                        $check_h_l = $hourly_leaves->where("year", "=", intval($jalali_from->format("Y")))->where("month", "=", intval($jalali_from->format("n")))->where("day", "=", intval($jalali_from->format("d")))->toArray();
                        if ($check_h_l) {
                            foreach ($check_h_l as $hourly) {
                                if ($hourly["is_approved"]) {
                                    if ($hourly["departure"] == $shift->arrival)
                                        $delay = 0;
                                    if ($hourly["arrival"] == $shift->departure)
                                        $acceleration = 0;
                                    $total_hourly_leave_duration += strtotime($hourly["arrival"]) - strtotime($hourly["departure"]);
                                } else
                                    $total_absence_duration += strtotime($hourly["arrival"]) - strtotime($hourly["departure"]);
                            }
                        }
                        if($total_hourly_leave_duration < (strtotime($shift->duration) / 2) && $total_leave_days < 3)
                            $operation = gmdate("H:i", $total_work_duration + $total_hourly_leave_duration);
                        elseif($total_leave_days >= 3) {
                            $total_absence_duration = strtotime($shift->departure) - strtotime($shift->arrival);
                            $color = "#FFFFAB";
                            $attendance = "غایب(سقف مرخصی)";
                            $total_leave_days++;
                        }
                        else{
                            $color = "#D3FFB5";
                            $attendance = "مرخصی";
                            $total_leave_days++;
                        }
                    }
                }
                else{
                    $status = 1;
                    $err_message = "عدم توازن ورود و خروج";
                    $color = "#fccfcf";
                    $attendance = "نامشخص";
                }
            }
            else{
                $is_in_daily_leaves = $daily_leaves
                    ->where("year", "=", intval($jalali_from->format("Y")))
                    ->where("month", "=", intval($jalali_from->format("n")))
                    ->where("day", "=", intval($jalali_from->format("j")))->first();
                if ($holiday || $is_in_daily_leaves && $is_in_daily_leaves->daily_leave->is_approved == 1) {
                    $total_work_duration = strtotime($shift->departure) - strtotime($shift->arrival);
                    $operation = $shift->duration;
                    if ($holiday)
                        $attendance = "بدون شیفت";
                    elseif ($is_in_daily_leaves && $total_leave_days < 3) {
                        $color = "#D3FFB5";
                        $attendance = "مرخصی";
                        $total_leave_days++;
                    }
                    elseif ($total_leave_days >= 3){
                        $total_absence_duration = strtotime($shift->departure) - strtotime($shift->arrival);
                        $color = "#FFFFAB";
                        $attendance = "غایب(سقف مرخصی)";
                        $total_leave_days++;
                    }
                }
                else {
                    $total_absence_duration = strtotime($shift->departure) - strtotime($shift->arrival);
                    $color = "#FFFFAB";
                    $attendance = "غایب";
                }
            }

            $result[] = [
                "location" => $location,
                "date" => $date,
                "day" => $day_of_week,
                "status" => $status,
                "err_message" => $err_message,
                "attendances" => $attendances,
                "total_work_duration" => gmdate("H:i",$total_work_duration),
                "operation" => $operation,
                "total_hourly_leave_duration" => gmdate("H:i",$total_hourly_leave_duration),
                "delay" => $delay,
                "acceleration" => $acceleration,
                "total_absence_duration" => gmdate("H:i",$total_absence_duration),
                "total_overtime_work_duration" => gmdate("H:i",$total_overtime_work_duration),
                "total_free_overtime_work_duration" => gmdate("H:i",$total_free_overtime_work_duration),
                "attendance" => $attendance,
                "color" => $color,
                "daily_wage" => $staff->daily_wage,
                "delay_amount" => payable_time($delay) * $delay_rate,
                "acceleration_amount" => payable_time($acceleration) * $acceleration_rate,
                "absence_amount" => payable_time(gmdate("H:i",$total_absence_duration)) * $absence_rate,
                "overtime_work_amount" => payable_time(gmdate("H:i",$total_overtime_work_duration)) * $overtime_rate ,
                "free_overtime_work_amount" => payable_time(gmdate("H:i",$total_free_overtime_work_duration)) * $overtime_rate,

            ];
            $jalali_from->addDay();
        }
        return $result;
    }
}
