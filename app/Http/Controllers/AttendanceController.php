<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Location;
use App\Models\Project;
use App\Models\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        $paginate = false;
        if ($request->has("staff_id") && $request->has("year") && $request->has("month"))
            $attendances = Attendance::with(["location","user","staff"])->where("staff_id","=",$request->staff_id)
                ->where("year","=",$request->year)->where("month","=",$request->month)->orderBy("timestamp")->get();
        else {
            $attendances = Attendance::with(["location", "user", "staff"])->orderBy("timestamp")->paginate(30, ['*'], 'attendance_page');
            $paginate = true;
        }
        $locations = Location::all();
        $users = User::query()->where("is_admin" , 0)->get();
        return view("{$this->agent}.attendance_index",[
            "locations" => $locations,
            "users" => $users,
            "attendances" => $attendances,
            "jalali_month_names" => $this->jalali_month_names(),
            "paginate" => $paginate,
            "staff_id" => $request->has("staff_id") ? $request->staff_id : '',
            "year" => $request->has("year") ? $request->year : '',
            "month" => $request->has("month") ? $request->month : '',
        ]);
    }


    public function create()
    {
        //
    }

    public function store(AttendanceRequest $request)
    {
        Gate::authorize("create","Attendances");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["user_id"] = Auth::id();
            $validated["type"] = $request->type;
            $time = $validated["time"];
            $date_array = explode("/",$validated["date"]);
            $converted_date = Verta::getGregorian($date_array[0],$date_array[1],$date_array[2]);
            $validated["timestamp"] = Carbon::parse(implode("-",$converted_date)." {$time}")->toDateTimeString();
            $validated["year"] = intval($date_array[0]);
            $validated["month"] = intval($date_array[1]);
            $validated["day"] = intval($date_array[2]);
            unset($validated["date"]);
            Attendance::query()->create($validated);
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        Gate::authorize("edit","Attendances");
        try {
            $attendance = Attendance::query()->with(["location","user","staff"])->findOrFail($id);
            $locations = Location::all();
            $users = User::query()->where("is_admin" , 0)->get();
            return view("{$this->agent}.edit_attendance",["locations" => $locations, "users" => $users, "attendance" => $attendance]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(AttendanceRequest $request, $id)
    {
        Gate::authorize("edit","Attendances");
        try {
            DB::beginTransaction();
            $attendance = Attendance::query()->findOrFail($id);
            $validated = $request->validated();
            $validated["user_id"] = Auth::id();
            $validated["type"] = $request->type;
            $time = $validated["time"];
            $date_array = explode("/",$validated["date"]);
            $converted_date = Verta::getGregorian($date_array[0],$date_array[1],$date_array[2]);
            $validated["timestamp"] = Carbon::parse(implode("-",$converted_date)." {$time}")->toDateTimeString();
            $validated["year"] = intval($date_array[0]);
            $validated["month"] = intval($date_array[1]);
            $validated["day"] = intval($date_array[2]);
            unset($validated["date"]);
            $attendance->update($validated);
            DB::commit();
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id)
    {
        Gate::authorize("destroy","Attendances");
        try {
            DB::beginTransaction();
            $attendance = Attendance::query()->findOrFail($id);
            $attendance->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function register_index()
    {
        Gate::authorize("index","RegisterAttendance");
        try {
            $locations = Location::query()->whereHas("project",function ($query){
                $query->whereIn("id",Project::get_permissions([])->pluck("id")->toArray());
            })->get();
            return view("{$this->agent}.register_attendance",["locations" => $locations]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function register(Request $request){
        Gate::authorize("index","RegisterAttendance");
        try {
            DB::beginTransaction();
            $validated["staff_id"] = $validated["user_id"] = Auth::id();
            $validated["type"] = $request->type;
            $validated["location_id"] = $request->location_id;
            $time = $validated["time"] = verta()->format("H:i");
            $date_array = explode("/",verta()->format("Y/n/d"));
            $converted_date = Verta::getGregorian($date_array[0],$date_array[1],$date_array[2]);
            $validated["timestamp"] = Carbon::parse(implode("-",$converted_date)." {$time}")->toDateTimeString();
            $validated["year"] = intval($date_array[0]);
            $validated["month"] = intval($date_array[1]);
            $validated["day"] = intval($date_array[2]);
            Attendance::query()->create($validated);
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
