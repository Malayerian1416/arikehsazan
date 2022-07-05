<?php

namespace App\Http\Controllers;

use App\Events\LeaveEvent;
use App\Http\Requests\DailyLeaveRequest;
use App\Models\DailyLeave;
use App\Models\LeaveFlow;
use App\Notifications\PushMessageLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DailyLeavesController extends Controller
{
    public function index()
    {
        Gate::authorize("index","DailyLeaves");
        try {
            $calender = $this->calender(30);
            $daily_leaves = DailyLeave::query()->with(["user","days","automation"])->whereHas("staff",function ($query){
                $query->where("staff_id","=",Auth::id());
            })->whereHas("automation")->get();
            return view("{$this->agent}.daily_leave_index",["daily_leaves" => $daily_leaves,"calender" => $calender]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(DailyLeaveRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","DailyLeaves");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["staff_id"] = $staff_id = isset($validated["staff_id"]) ?: Auth::id();
            $validated["user_id"] = Auth::id();
            $daily_leave = DailyLeave::query()->create($validated);
            foreach ($validated["selected_dates"] as $date){
                $seperated = explode("/",$date);
                if(DailyLeave::check_duplicates($staff_id,$seperated[0],$seperated[1],$seperated[2]))
                    $daily_leave->days()->create(["year" => $seperated[0],"month" => $seperated[1],"day" => $seperated[2],"timestamp" => $this->get_gregorian_timestamp($date)]);
                else {
                    DB::rollBack();
                    return redirect()->back()->with(["action_error" => "تاریخ " . $date . " به عنوان مرخصی برای شما ثبت شده است"]);
                }
            }
            $automate = LeaveFlow::automate();
            $daily_leave->automation()->create($automate);
            if ($automate["is_finished"] == 1)
                $daily_leave->update(["is_approved" => 1]);
            if ($request->hasFile('leave_docs')){
                foreach ($request->file('leave_docs') as $file)
                    Storage::disk('daily_leave_docs')->put($daily_leave->id,$file);
            }
            $daily_leave->automation->signs()->create(["user_id" => Auth::id(),"sign" => Auth::user()->sign]);
            DB::commit();
            $message = "درخواست مرخصی جدید به صندوق اتوماسیون شما ارسال شده است";
            $this->send_push_notification(PushMessageLeave::class,$message,"role_id",$daily_leave->automation->current_role_id);
            $this->send_event_notification(LeaveEvent::class,$daily_leave->automation,$message);
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("edit","DailyLeaves");
        try {
            $docs = '';
            if (Storage::disk('daily_leave_docs')->exists($id))
                $docs = Storage::disk('daily_leave_docs')->allFiles($id);
            $calender = $this->calender(30);
            $leave = DailyLeave::query()->with(["user","days","automation"])->findOrFail($id);
            return view("{$this->agent}.edit_daily_leave",["leave" => $leave,"calender" => $calender, "docs" => $docs]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(DailyLeaveRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","DailyLeaves");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["staff_id"] = $staff_id = isset($validated["staff_id"]) ?: Auth::id();
            $validated["user_id"] = Auth::id();
            $daily_leave = DailyLeave::query()->findOrFail($id);
            $daily_leave->update($validated);
            $daily_leave->days()->delete();
            foreach ($validated["selected_dates"] as $date){
                $seperated = explode("/",$date);
                if(DailyLeave::check_duplicates($staff_id,$seperated[0],$seperated[1],$seperated[2]))
                    $daily_leave->days()->create(["year" => $seperated[0],"month" => $seperated[1],"day" => $seperated[2],"timestamp" => $this->get_gregorian_timestamp($date)]);
                else {
                    DB::rollBack();
                    return redirect()->back()->with(["action_error" => "تاریخ " . $date . " به عنوان مرخصی برای شما ثبت شده است"]);
                }
            }
            $automate = LeaveFlow::automate();
            $daily_leave->automation()->update($automate);
            if ($automate["is_finished"] == 1)
                $daily_leave->update(["is_approved" => 1]);
            if ($request->hasFile('leave_docs')){
                foreach ($request->file('leave_docs') as $file)
                    Storage::disk('daily_leave_docs')->put($daily_leave->id,$file);
            }
            DB::commit();
            $message = "درخواست مرخصی جدید پس از ویرایش به صندوق اتوماسیون شما ارسال شده است";
            $this->send_push_notification(PushMessageLeave::class,$message,"role_id",$daily_leave->automation->current_role_id);
            $this->send_event_notification(LeaveEvent::class,$daily_leave->automation,$message);
            return redirect()->route("DailyLeaves.index")->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("destroy","DailyLeaves");
        try {
            DB::beginTransaction();
            $hourly_leave = DailyLeave::query()->findOrFail($id);
            $hourly_leave->automation->delete();
            $hourly_leave->delete();
            Storage::disk("daily_leave_docs")->deleteDirectory($id);
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
}
