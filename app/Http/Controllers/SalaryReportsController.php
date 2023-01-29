<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaryReportRequest;
use App\Models\Attendance;
use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Support\Facades\Gate;
use Throwable;

class SalaryReportsController extends Controller
{
    public function salary_report_index(){
        Gate::authorize("salary_reports_index","Reports");
        try {
           $staffs = User::query()->with("work_shift")->where("is_staff","=",1)->get();
           $work_shifts = WorkShift::all();
           return view("{$this->agent}.salary_reports",[
               "staffs" => $staffs,
               "work_shifts" => $work_shifts
           ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function make_salary_report(SalaryReportRequest $request){
        Gate::authorize("salary_reports_make","Reports");
        try {
            $totals = [];
            $staffs = User::query()->with("work_shift")->where("is_staff","=",1)->get();
            $work_shifts = WorkShift::all();
            $validated = $request->validated();
            $holidays = $request->has("holidays") ? $validated["holidays"] : [];
            $staff = User::query()->findOrFail($validated["staff_id"]);
            $results = Attendance::get_working_days($staff->id,$this->get_gregorian_timestamp($validated["from_date"]),$this->get_gregorian_timestamp($this->create_jalali_date($validated["to_date"])),$validated["work_shift_id"],$holidays);
            if ($results){
                $total_wage = 0;
                $total_days = count($results);
                $total_Presence_day = 0;
                $total_holidays = 0;
                $total_absence_day = 0;
                $total_delay = 0;
                $total_acceleration = 0;
                $total_leaves = 0;
                $total_overtime_work = 0;
                $total_absence = 0;
                $counter = 0;
                $total_absence_day_illegal = 0;
                foreach ($results as $result) {
                    if ($result["status"] == 0) {
                        $total_wage += $result["daily_wage"];
                        switch ($result["attendance"]) {
                            case "حاضر":
                            {
                                $total_Presence_day++;
                                break;
                            }
                            case "بدون شیفت":
                            {
                                $total_holidays++;
                                break;
                            }
                            case "مرخصی":
                            {
                                $total_leaves++;
                                break;
                            }
                            case "غایب":
                            {
                                $total_absence_day++;
                                break;
                            }
                            case "غایب(سقف مرخصی)":
                            {
                                $total_absence_day_illegal++;
                                break;
                            }
                        }
                        $total_overtime_work += $result["overtime_work_amount"] + $result["free_overtime_work_amount"];
                        $total_delay += $result["delay_amount"];
                        $total_acceleration += $result["acceleration_amount"];
                        $total_absence += $result["absence_amount"];
                        $counter++;
                    }
                }
                $total_payable = ($total_wage + $total_overtime_work) - ($total_delay + $total_acceleration + $total_absence);
                $totals = [
                    "total_wage" => number_format($total_wage),
                    "total_days" => $total_days,
                    "total_Presence_day" => $total_Presence_day,
                    "total_holidays" => $total_holidays,
                    "total_absence_day" => $total_absence_day,
                    "total_delay" => number_format($total_delay),
                    "total_acceleration" => number_format($total_acceleration),
                    "total_leaves" => $total_leaves,
                    "total_absence_day_illegal" => $total_absence_day_illegal,
                    "total_overtime_work" => number_format($total_overtime_work),
                    "total_absence" => number_format($total_absence),
                    "total_payable" => number_format($total_payable)
                ];
            }
            return view("{$this->agent}.salary_reports",[
                "staffs" => $staffs,
                "work_shifts" => $work_shifts,
                "results" => $results,
                "totals" => $totals,
                "old_data" => $validated
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }

    }
}
