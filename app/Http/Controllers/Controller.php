<?php

namespace App\Http\Controllers;

use App\Events\LeaveEvent;
use App\Events\NewLeaveAutomation;
use App\Models\Contract;
use App\Models\User;
use App\Notifications\PushMessageLeave;
use Hekmatinasser\Verta\Verta;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Agent\Agent;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        $agent = new Agent();
        if ($agent->isDesktop())
            $this->agent = "desktop_dashboard";
        else if($agent->isPhone() || $agent->isTablet())
            $this->agent = "phone_dashboard";
        else if ($agent->robot())
            return view("errors/cant_detect_device");
        else
            return view("errors/cant_detect_device");
        return false;
    }
    public function gregorian_date_convert($date,$symbol){
        try {
            $date_array = explode($symbol,$date);
            $converted_date = Verta::getGregorian($date_array[0],$date_array[1],$date_array[2]);
            return Carbon::parse(implode("-",$converted_date)." 00:00:00")->toDateTimeString();
        }
        catch (Throwable $ex){
            abort(501);
        }
    }
    public function contract_number(): string
    {
        $number = "پ"."/".verta()->format("Y/m/").rand(101,999);
        while (Contract::query()->where("contract_row",$number)->get()->isNotEmpty())
            $number = "پ"."/".verta()->format("Y/m/").rand(101,999);
        return $number;
    }
    public function get_agent(): Agent
    {
        return new Agent();
    }
    public function calender(int $days): array
    {
        $calender = [];
        $date = verta();
        $start_week = verta();
        $date->addDay();
        $start_week->addDay();
        $start_week->startWeek();
        for ($k = 0; $k < $date->dayOfWeek; $k++) {
            $calender [0][] = [
                "day_off" => 2,
                "day" => $start_week->day,
                "month" => $start_week->month,
                "day_of_week" => $start_week->dayOfWeek,
                "month_name" => $start_week->format("%B"),
                "year" => $start_week->year
            ];
            $start_week->addDay();
        }
        for ($i = 0; $i <= $days; $i++){
            for ($j = $date->dayOfWeek; $j < 7; $j++) {
                $day_off = $date->isFriday() ? 1:0;
                $calender [$i][] = [
                    "day_off" => $day_off,
                    "day" => $date->day,
                    "month" => $date->month,
                    "day_of_week" => $date->dayOfWeek,
                    "month_name" => $date->format("%B"),
                    "year" => $date->year
                ];
                $date->addDay();
            }
            $i += 6 - $date->dayOfWeek;
        }
        return $calender;
    }
    function jalali_month_names(): array
    {
        return [
            1 => "فروردین",
            2 => "اردیبهشت",
            3 => "خرداد",
            4 => "تیر",
            5 => "مرداد",
            6 => "شهریور",
            7 => "مهر",
            8 => "آبان",
            9 => "آذر",
            10 => "دی",
            11 => "بهمن",
            12 => "اسفند"
        ];
    }
    public function send_event_notification($class,$source,$message){
        event(new $class($source,$message));
    }
    public function send_push_notification($class,$message,$search_column,$search_value){
        Notification::send(User::query()->where($search_column,"=",$search_value)->get(),new $class($message));
    }
    public function get_gregorian_timestamp($date){
        $date = explode("/",$date);
        $date = Verta::getGregorian($date[0],$date[1],$date[2]);
        return date(implode("-",$date));
    }
    public function create_jalali_date($date): string
    {
        $date = explode("/",$date);
        $date = Verta::createJalaliDate($date[0],$date[1],$date[2]);
        $date->addDay();
        return $date->format("Y/n/j");
    }
}
