<?php

namespace App\Http\Controllers;

use App\Events\LeaveEvent;
use App\Http\Requests\HourlyLeaveRequest;
use App\Http\Requests\LeaveAttendanceRegistrationRequest;
use App\Models\Attendance;
use App\Models\HourlyLeave;
use App\Models\LeaveFlow;
use App\Models\Location;
use App\Models\User;
use App\Notifications\PushMessageLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class HourlyLeavesController extends Controller
{

    public function index()
    {
        Gate::authorize("index","HourlyLeaves");
        try {
            $locations = Location::all();
            $status = HourlyLeave::status();
            if ($status["flag"])
                return view("{$this->agent}.hourly_leave_index",["current_leave" => $status["leave"],"locations" => $locations]);
            else {
                $hourly_leaves = HourlyLeave::query()->with(["user","automation","location"])->whereHas("staff",function ($query){
                $query->where("staff_id","=",Auth::id());
                })->whereHas("automation")->get();
                return view("{$this->agent}.hourly_leave_index",["locations" => $locations,"hourly_leaves" => $hourly_leaves]);
            }
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(HourlyLeaveRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","HourlyLeaves");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["user_id"] = $validated["staff_id"] = Auth::id();
            $validated["year"] = verta()->format("Y");
            $validated["month"] = verta()->format("n");
            $validated["day"] = verta()->format("d");
            $validated["departure"] = $validated["departure"] ?: verta()->format("H:i");
            $validated["timestamp"] = date("Y-m-d H:i:s");
            $hourly_leave = HourlyLeave::query()->create($validated);
            if ($request->hasFile('leave_docs')){
                foreach ($request->file('leave_docs') as $file)
                    Storage::disk('hourly_leave_docs')->put($hourly_leave->id,$file);
            }
            $automate = LeaveFlow::automate();
            $hourly_leave->automation()->create($automate);
            if ($automate["is_finished"] == 1)
                $hourly_leave->update(["is_approved" => 1]);
            $hourly_leave->automation->signs()->create(["user_id" => Auth::id(),"sign" => Auth::user()->sign]);
            $message = "درخواست مرخصی جدید به صندوق اتوماسیون شما ارسال شده است";
            $this->send_push_notification(PushMessageLeave::class,$message,"role_id",$hourly_leave->automation->current_role_id);
            $this->send_event_notification(LeaveEvent::class,$hourly_leave->automation,$message);
            DB::commit();
            return redirect()->route("HourlyLeaves.index")->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("edit","HourlyLeaves");
        try {
            $docs = '';
            if (Storage::disk('hourly_leave_docs')->exists($id))
                $docs = Storage::disk('hourly_leave_docs')->allFiles($id);
            $leave = HourlyLeave::query()->with(["user","automation","location"])->findOrFail($id);
            return view("{$this->agent}.edit_hourly_leave",["leave" => $leave, "docs" => $docs]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(HourlyLeaveRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","HourlyLeaves");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["user_id"] = $validated["staff_id"] = Auth::id();
            $validated["year"] = verta()->format("Y");
            $validated["month"] = verta()->format("n");
            $validated["day"] = verta()->format("d");
            $validated["departure"] = $validated["departure"] ?: verta()->format("H:i");
            $validated["timestamp"] = date("Y-m-d H:i:s");
            $hourly_leave = HourlyLeave::query()->findOrFail($id);
            $hourly_leave->update($validated);
            if ($request->hasFile('leave_docs')){
                foreach ($request->file('leave_docs') as $file)
                    Storage::disk('hourly_leave_docs')->put($hourly_leave->id,$file);
            }
            $automate = LeaveFlow::automate();
            $hourly_leave->automation()->update($automate);
            if ($automate["is_finished"] == 1)
                $hourly_leave->update(["is_approved" => 1]);
            $message = "درخواست مرخصی جدید پس از ویرایش به صندوق اتوماسیون شما ارسال شده است";
            $this->send_push_notification(PushMessageLeave::class,$message,"role_id",$hourly_leave->automation->current_role_id);
            $this->send_event_notification(LeaveEvent::class,$hourly_leave->automation,$message);
            DB::commit();
            return redirect()->route("HourlyLeaves.index")->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("destroy","HourlyLeaves");
        try {
            DB::beginTransaction();
            $hourly_leave = HourlyLeave::query()->findOrFail($id);
            $hourly_leave->automation->delete();
            $hourly_leave->delete();
            Storage::disk("hourly_leave_docs")->deleteDirectory($id);
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function delete_doc(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            Storage::disk($request->input("type"))->delete($request->input("doc"));
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function registration(LeaveAttendanceRegistrationRequest $request,$id): \Illuminate\Http\RedirectResponse
    {
        try {
            $validated = $request->validated();
            $location = Location::query()->where("hash","=", $validated["location_id"])->first();
            if ($location){
                DB::beginTransaction();
                $hourly_leave = HourlyLeave::query()->findOrFail($id);
                $staff = User::query()->findOrFail(Auth::id());
                $staff_shift = $staff->work_shift;
                $hourly_leave->update(["arrival" => verta()->format("H:i"), "location_id" => $location->id, "current_status" => 0]);
                $departure_hour = verta($hourly_leave->departure)->format("H");
                $departure_minute = verta($hourly_leave->departure)->format("i");
                if (strtotime(date("H:i")) >= strtotime($staff_shift->departure)){
                    Attendance::query()->create([
                        "staff_id" => Auth::id(),
                        "user_id" => Auth::id(),
                        "location_id" => $hourly_leave->location_id ?: 1,
                        "type" => "absence",
                        "year" => verta()->format("Y"),
                        "month" => verta()->format("n"),
                        "day" => verta()->format("j"),
                        "time" => $hourly_leave->departure,
                        "timestamp" => date("Y-m-d {$departure_hour}:{$departure_minute}:00")
                    ]);
                }
                Attendance::query()->create([
                    "staff_id" => Auth::id(),
                    "user_id" => Auth::id(),
                    "location_id" => $location->id,
                    "type" => "absence",
                    "year" => verta()->format("Y"),
                    "month" => verta()->format("n"),
                    "day" => verta()->format("j"),
                    "time" => $hourly_leave->departure,
                    "timestamp" => date("Y-m-d {$departure_hour}:{$departure_minute}:00")
                ]);
                Attendance::query()->create([
                    "staff_id" => Auth::id(),
                    "user_id" => Auth::id(),
                    "location_id" => $location->id,
                    "type" => "presence",
                    "year" => verta()->format("Y"),
                    "month" => verta()->format("n"),
                    "day" => verta()->format("j"),
                    "time" => verta()->format("H:i"),
                    "timestamp" => date("Y-m-d H:i:00")
                ]);
                DB::commit();
                return redirect()->route("HourlyLeaves.index")->with(["result" => "saved"]);
            }
            else
                return redirect()->back()->with(["action_error" => "موقعیت مکانی ارسال شده معتبر نمی باشد."]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
