<?php

namespace App\Console;

use App\Models\Attendance;
use App\Models\HourlyLeave;
use App\Models\WorkShift;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $work_shifts = WorkShift::all();
        foreach ($work_shifts as $shift){
            $schedule->call(function () use ($shift){
                $hourly_leave = HourlyLeave::query()->whereHas("staff.work_shift",function ($query) use ($shift){
                    $query->where("work_shifts.departure","=",$shift->departure);
                })->where("arrival","=",null)->get();
                foreach ($hourly_leave as $leave){
                    if (strtotime($shift->departure) <= strtotime(verta()->format("H:i"))){
                        $time = date("H:i");
                        Storage::disk("system_log")->append("schedule_log.txt","{$time} - shift:{$shift->departure} - leave_id:{$leave->id} - staff_id:{$leave->staff_id}",PHP_EOL);
                        $leave->update(["arrival" => $shift->departure]);
                        $departure_hour = verta($leave->departure)->format("H");
                        $departure_minute = verta($leave->departure)->format("i");
                        $arrival_hour = verta($shift->departure)->format("H");
                        $arrival_minute = verta($shift->departure)->format("i");
                        Attendance::query()->create([
                            "staff_id" => $leave->staff_id,
                            "user_id" => $leave->user_id,
                            "location_id" => $leave->location_id ?: 1,
                            "type" => "absence",
                            "year" => verta()->format("Y"),
                            "month" => verta()->format("n"),
                            "day" => verta()->format("j"),
                            "time" => $leave->departure,
                            "timestamp" => date("Y-m-d {$departure_hour}:{$departure_minute}:00")
                        ]);
                        Attendance::query()->create([
                            "staff_id" => $leave->staff_id,
                            "user_id" => $leave->user_id,
                            "location_id" => $leave->location_id ?: 1,
                            "type" => "absence",
                            "year" => verta()->format("Y"),
                            "month" => verta()->format("n"),
                            "day" => verta()->format("j"),
                            "time" => $leave->departure,
                            "timestamp" => date("Y-m-d {$departure_hour}:{$departure_minute}:00")
                        ]);
                        Attendance::query()->create([
                            "staff_id" => $leave->staff_id,
                            "user_id" => $leave->user_id,
                            "location_id" => $leave->location_id ?: 1,
                            "type" => "presence",
                            "year" => verta()->format("Y"),
                            "month" => verta()->format("n"),
                            "day" => verta()->format("j"),
                            "time" => verta($shift->departure)->format("H:i"),
                            "timestamp" => date("Y-m-d {$arrival_hour}:{$arrival_minute}:00")
                        ]);
                    }
                }
            })->dailyAt($shift->departure);
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
