<?php

namespace App\Http\Controllers;

use App\Events\LeaveEvent;
use App\Http\Requests\HourlyLeaveRequest;
use App\Models\Attendance;
use App\Models\DailyLeave;
use App\Models\HourlyLeave;
use App\Models\LeaveDay;
use App\Models\LeaveFlow;
use App\Models\User;
use App\Notifications\PushMessageLeave;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class LeaveController extends Controller
{

    public function index()
    {
        Gate::authorize("index","Leaves");
        try {
            $users = User::query()->where("is_staff", 1)->get();
            $daily_leaves = DailyLeave::query()->with(["user","staff","days"])->where("is_approved","=",1)->get();
            $hourly_leaves = HourlyLeave::query()->with(["staff","user"])->where("is_approved","=",1)->get();
            return view("{$this->agent}.leave_index", ["users" => $users,"daily_leaves" => $daily_leaves,"hourly_leaves" => $hourly_leaves]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","Leaves");
        try {
            if ($request->input("staff_id") && $request->input("data")){
                DB::beginTransaction();
                $staff_id = $request->input("staff_id");
                $staff = User::query()->findOrFail($staff_id);
                $staff_shift = $staff->work_shift;
                foreach ($request->input("data") as $data){
                    $data = json_decode($data,true);
                    $date = explode("/",$data["leave_date"]);
                    $timestamp = implode("-",Verta::getGregorian(intval($date[0]),intval($date[1]),intval($date[2])));
                    switch ($data["type"]){
                        case "daily":{
                            $daily_leave = DailyLeave::query()->create(["staff_id" => $staff_id,"user_id" => Auth::id(),"reason" => "توسط کاربر","is_approved" => 1]);
                            $daily_leave->days()->create([
                                "year" => intval($date[0]),
                                "month" => intval($date[1]),
                                "day" => intval($date[2]),
                                "timestamp" => $timestamp
                            ]);
                            break;
                        }
                        case "hourly":{
                            $hourly_leave = HourlyLeave::query()->create([
                                "staff_id" => $staff_id,
                                "user_id" => Auth::id(),
                                "year" => intval($date[0]),
                                "month" => intval($date[1]),
                                "day" => intval($date[2]),
                                "departure" => $data["departure_time"],
                                "arrival" => $data["arrival_time"],
                                "reason" => "توسط کاربر",
                                "timestamp" => date("y-m-d H:i",strtotime($timestamp)),
                                "is_approved" => 1,
                                "current_status" => 0
                            ]);
                            if (strtotime($hourly_leave->arrival) >= strtotime($staff_shift->departure)){
                                Attendance::query()->create([
                                    "staff_id" => $staff_id,
                                    "user_id" => Auth::id(),
                                    "location_id" => $hourly_leave->location_id ?: 1,
                                    "type" => "absence",
                                    "year" => intval($date[0]),
                                    "month" => intval($date[1]),
                                    "day" => intval($date[2]),
                                    "time" => $hourly_leave->departure,
                                    "timestamp" => date("y-m-d H:i",strtotime($timestamp." $hourly_leave->departure"))
                                ]);
                            }
                            Attendance::query()->create([
                                "staff_id" => $staff_id,
                                "user_id" => Auth::id(),
                                "location_id" => $hourly_leave->location_id ?: 1,
                                "type" => "absence",
                                "year" => intval($date[0]),
                                "month" => intval($date[1]),
                                "day" => intval($date[2]),
                                "time" => $hourly_leave->departure,
                                "timestamp" => date("y-m-d H:i",strtotime($timestamp." $hourly_leave->departure"))
                            ]);
                            Attendance::query()->create([
                                "staff_id" => $staff_id,
                                "user_id" => Auth::id(),
                                "location_id" => $hourly_leave->location_id ?: 1,
                                "type" => "presence",
                                "year" => intval($date[0]),
                                "month" => intval($date[1]),
                                "day" => intval($date[2]),
                                "time" => $hourly_leave->arrival,
                                "timestamp" => date("y-m-d H:i",strtotime($timestamp." $hourly_leave->arrival"))
                            ]);
                            break;
                        }
                    }
                }
                DB::commit();
                return redirect()->back()->with(["result" => "saved"]);
            }
            else
                return redirect()->back()->with(["action_error" => "انتخاب پرسنل و درج حداقل یه نوع مرخصی الزامی می باشد."]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("edit","Leaves");
        try {
            $sep_id = explode("@",$id)[0];
            $type = explode("@",$id)[1];
            $docs = '';
            $staffs = User::query()->where("is_staff","=",1)->get();
            switch ($type){
                case "daily":{
                    $leave = LeaveDay::query()->with("daily_leave")->findOrFail($sep_id);
                    if (Storage::disk('daily_leave_docs')->exists($leave->daily_leave->id))
                        $docs = Storage::disk('daily_leave_docs')->allFiles($leave->daily_leave->id);
                    return view("{$this->agent}.edit_daily_manage_leave",["leave" => $leave,"docs" => $docs,"users" => $staffs]);
                }
                case "hourly":{
                    if (Storage::disk('hourly_leave_docs')->exists($sep_id))
                        $docs = Storage::disk('hourly_leave_docs')->allFiles($sep_id);
                    $leave = HourlyLeave::query()->findOrFail($sep_id);
                    return view("{$this->agent}.edit_hourly_manage_leave",["leave" => $leave,"docs" => $docs,"users" => $staffs]);
                }
            }
            return redirect()->back();
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }


    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","Leaves");
            switch ($request->leave_type){
                case "daily":{
                    $request->validate([
                        "staff_id" => "required",
                        "leave_date" => "required|jdate:Y/m/d",
                    ],[
                        "staff_id.required" => "انتخاب پرسنل الزامی می باشد",
                        "leave_date.required" => "درج تاریخ الزامی می باشد",
                        "leave_date.jdate" => "فرمت تاریخ صحیح نمی باشد",
                    ]);
                    $request["user_id"] = Auth::id();
                    $timestamp = $this->get_gregorian_timestamp($request->leave_date);
                    $date = explode("/",$request->leave_date);
                    $request["year"] = intval($date[0]);
                    $request["month"] = intval($date[1]);
                    $request["day"] = intval($date[2]);
                    $leave_day = LeaveDay::query()->findOrFail($id);
                    $leave_day->update([
                        "year" => intval($date[0]),
                        "month" => intval($date[1]),
                        "day" => intval($date[2]),
                        "timestamp" => $timestamp
                    ]);
                    $leave_day->daily_leave()->update([
                        "staff_id" => $request->staff_id,
                        "user_id" => Auth::id(),
                        "reason" => $request->reason,
                        "is_approved" => 1
                    ]);
                    break;
                }
                case "hourly":{
                    $request->validate([
                        "staff_id" => "required",
                        "leave_date" => "required|jdate:Y/m/d",
                        "departure" => "required",
                        "arrival" => "required"
                    ],[
                        "staff_id.required" => "انتخاب پرسنل الزامی می باشد",
                        "leave_date.required" => "درج تاریخ الزامی می باشد",
                        "leave_date.jdate" => "فرمت تاریخ صحیح نمی باشد",
                        "departure.required" => "درج زمان شروع الزامی می باشد",
                        "arrival.required" => "درج زمان پایان الزامی می باشد",
                    ]);
                    $hourly_leave = HourlyLeave::query()->findOrFail($id);
                    $date = explode("/",$request->leave_date);
                    $request["year"] = intval($date[0]);
                    $request["month"] = intval($date[1]);
                    $request["day"] = intval($date[2]);
                    $request["user_id"] = Auth::id();
                    $timestamp = implode("-",Verta::getGregorian(intval($date[0]),intval($date[1]),intval($date[2])));
                    $request["timestamp"] = date("y-m-d",strtotime($timestamp));
                    unset($request["leave_date"]);
                    $absence = Attendance::query()->where("staff_id","=",$request->staff_id)->where("type","=","absence")
                        ->where("year","=",intval($date[0]))->where("month","=",intval($date[1]))->where("day","=",intval($date[2]))
                        ->where("time","=",$hourly_leave->departure)->first();
                    $presence = Attendance::query()->where("staff_id","=",$request->staff_id)->where("type","=","presence")
                        ->where("year","=",intval($date[0]))->where("month","=",intval($date[1]))->where("day","=",intval($date[2]))
                        ->where("time","=",$hourly_leave->arrival)->first();
                    $hourly_leave->update($request->toArray());
                    if ($absence) {
                        $absence->update([
                            "staff_id" => $request->staff_id,
                            "user_id" => Auth::id(),
                            "location_id" => $hourly_leave->location_id ?: 1,
                            "type" => "absence",
                            "year" => intval($date[0]),
                            "month" => intval($date[1]),
                            "day" => intval($date[2]),
                            "time" => $hourly_leave->departure,
                            "timestamp" => date("Y-m-d H:i", strtotime($timestamp . " $hourly_leave->departure"))
                        ]);
                    }
                    if ($presence) {
                        $presence->update([
                            "staff_id" => $request->staff_id,
                            "user_id" => Auth::id(),
                            "location_id" => $hourly_leave->location_id ?: 1,
                            "type" => "presence",
                            "year" => intval($date[0]),
                            "month" => intval($date[1]),
                            "day" => intval($date[2]),
                            "time" => $hourly_leave->arrival,
                            "timestamp" => date("Y-m-d H:i", strtotime($timestamp . " $hourly_leave->arrival"))
                        ]);
                    }
                    if ($request->hasFile('leave_docs')){
                        foreach ($request->file('leave_docs') as $file)
                            Storage::disk('hourly_leave_docs')->put($hourly_leave->id,$file);
                    }
                    break;
                }
            }
            DB::commit();
            return redirect()->route("Leaves.index")->with(["result" => "updated"]);
    }

    public function destroy($id,Request $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("destroy","Leaves");
        try {
            switch ($request->input("type")){
                case "daily":{
                    if (Storage::disk('daily_leave_docs')->exists($id))
                        Storage::disk('daily_leave_docs')->deleteDirectory($id);
                    $leave = DailyLeave::query()->findOrFail($id);
                    $leave->delete();
                }
                case "hourly":{
                    if (Storage::disk('hourly_leave_docs')->exists($id))
                        Storage::disk('hourly_leave_docs')->deleteDirectory($id);
                    $leave = HourlyLeave::query()->findOrFail($id);
                    $leave->delete();
                    break;
                }
            }
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
