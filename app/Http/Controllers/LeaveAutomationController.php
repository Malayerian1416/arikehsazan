<?php

namespace App\Http\Controllers;

use App\Events\LeaveEvent;
use App\Events\NewLeaveAutomation;
use App\Models\Attendance;
use App\Models\DailyLeave;
use App\Models\HourlyLeave;
use App\Models\Invoice;
use App\Models\InvoiceFlow;
use App\Models\LeaveAutomation;
use App\Models\LeaveFlow;
use App\Models\User;
use App\Notifications\PushMessageLeave;
use App\Notifications\PushNewLeave;
use App\Notifications\PushReferLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Throwable;

class LeaveAutomationController extends Controller
{
    public function get_automation_items(){
        Gate::authorize("automation","LeaveAutomation");
        try {
            $leave_automations_inbox = LeaveAutomation::query()
                ->with(["automationable.staff","automationable.user"])->where("current_role_id","=",Auth::user()->role->id)->get();
            $leave_automations_outbox = LeaveAutomation::query()
                ->with(["automationable.staff","automationable.user"])->whereHas("signs" , function($query){
                    $query->where("user_id","=",Auth::id());
            })->get();
            return view("{$this->agent}.leave_automation", ["leave_automations_inbox" => $leave_automations_inbox,"leave_automations_outbox" => $leave_automations_outbox]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function view_details($id,$type)
    {
        Gate::authorize("details","LeaveAutomation");
        try {
            $leave = '';
            $docs = '';
            switch (urldecode($type)){
                case ("Daily"):{
                    $leave = DailyLeave::query()->with(["automation.signs","automation.comments","days" => function($query){
                        $query->orderBy("year","ASC")->orderBy("month","ASC")->orderBy("day","ASC");},"staff"])->findOrFail($id);
                    if (Storage::disk('daily_leave_docs')->exists($id))
                        $docs = Storage::disk('daily_leave_docs')->allFiles($id);
                    break;
                }
                case ("Hourly"):{
                    $leave = HourlyLeave::query()->with(["automation.signs","automation.comments","staff","location"])->findOrFail($id);
                    if (Storage::disk('hourly_leave_docs')->exists($id))
                        $docs = Storage::disk('hourly_leave_docs')->allFiles($id);
                    break;
                }
                default:{
                    abort(404);
                }
            }
            $staff_id = $leave->staff->id;
            $leave_history = LeaveAutomation::query()->with(["automationable.staff","automationable.user"])->whereHas("automationable",
                function ($query) use($staff_id,$id){$query->where("staff_id","=",$staff_id)->where("id","<>",$id);})->get();
            return view("{$this->agent}.leave_automation_details",[
                "leave" => $leave,
                "month_names" => $this->jalali_month_names(),
                "leave_history" => $leave_history,
                "main_role" => LeaveFlow::MainRole(),
                "type" => $type,
                "docs" => $docs
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }


    public function automate_sending(Request $request,$id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("send","InvoiceAutomation");
        try {
            DB::beginTransaction();
            $automation = LeaveAutomation::query()->findOrFail($id);
            $automation->update(LeaveFlow::automate());
            if ($request->input('comment') != null)
                $automation->comments()->create(["user_id" => Auth::id(), "comment" => $request->comment]);
            if ($automation->signs()->where("user_id","=",Auth::id())->count() == 0)
                $automation->signs()->create(["user_id" => Auth::id(),"sign" => Auth::user()->sign]);
            DB::commit();
            $message = "درخواست مرخصی جدید به صندوق اتوماسیون شما ارسال شده است";
            $this->send_push_notification(PushMessageLeave::class,$message,"role_id",$automation->current_role_id);
            //$this->send_event_notification(LeaveEvent::class,$automation,$message);
            return redirect()->route("LeaveAutomation.automation")->with(["result" => "sent"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function refer($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("refer","LeaveAutomation");
        try {
            DB::beginTransaction();
            $automation = LeaveAutomation::query()->findOrFail($id);
            LeaveFlow::refer($id);
            DB::commit();
            $message = "درخواست مرخصی به صندوق اتوماسیون شما ارجاع شده است";
            $this->send_push_notification(PushMessageLeave::class,$message,"role_id",$automation->current_role_id);
            //$this->send_event_notification(LeaveEvent::class,$automation,$message);
            return redirect()->route("LeaveAutomation.automation")->with(["result" => "referred"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function approve($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("approve","LeaveAutomation");
        try {
            DB::beginTransaction();
            $automation = LeaveAutomation::query()->findOrFail($id);
            $automation->update(LeaveFlow::automate());
            $automation->automationable->update(["is_approved" => 1]);
            if ($automation->signs()->where("user_id","=",Auth::id())->count() == 0)
                $automation->signs()->create(["user_id" => Auth::id(),"sign" => Auth::user()->sign]);
            DB::commit();
            $message = '';
            switch ($automation->automationable_type){
                case "App\Models\DailyLeave":{
                    $message = "با درخواست مرخصی روزانه شما ایجاد شده در تاریخ ".
                        verta($automation->created_at)->format("Y/m/d")." به مدت ".
                        count($automation->automationable->days->toArray())." روز موافقت شده است";
                    break;
                }
                case "App\Models\HourlyLeave":{
                    $message = "با درخواست مرخصی ساعتی شما ایجاد شده در تاریخ ".
                        verta($automation->created_at)->format("Y/m/d")." به مدت ".
                        gmdate("H:i",strtotime($automation->automationable->arrival) - strtotime($automation->automationable->departure)) . " موافقت شده است";
                    break;
                }
            }

            $this->send_push_notification(PushMessageLeave::class,$message,"id",$automation->automationable->staff_id);
            return redirect()->route("LeaveAutomation.automation")->with(["result" => "approved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function reject($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("reject","LeaveAutomation");
        try {
            DB::beginTransaction();
            $automation = LeaveAutomation::query()->findOrFail($id);
            $automation->update([
                "previous_role_id"=>0,
                "current_role_id"=>0,
                "next_role_id"=>0,
                "is_read"=> 1,
                "is_finished" => 1
            ]);
            if ($automation->signs()->where("user_id","=",Auth::id())->count() == 0)
                $automation->signs()->create(["user_id" => Auth::id(),"sign" => Auth::user()->sign]);
            $automation->automationable->update(["is_approved" => 0]);
            DB::commit();
            $message = '';
            switch ($automation->automationable_type){
                case "App\Models\DailyLeave":{
                    $message = "با درخواست مرخصی روزانه شما ایجاد شده در تاریخ ".
                        verta($automation->created_at)->format("Y/n/d")." به مدت ".
                        count($automation->automationable->days->toArray())." روز موافقت نشده است";
                    break;
                }
                case "App\Models\HourlyLeave":{
                    $message = "با درخواست مرخصی ساعتی شما ایجاد شده در تاریخ ".
                        verta($automation->created_at)->format("Y/n/d")." به مدت ".
                        gmdate("H:i",strtotime($automation->automationable->arrival) - strtotime($automation->automationable->departure)) . " موافقت نشده است";
                    break;
                }
            }
            $this->send_push_notification(PushMessageLeave::class,$message,"id",$automation->automationable->staff_id);
            return redirect()->route("LeaveAutomation.automation")->with(["result" => "rejected"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function view_sent_details($id,$type){
        Gate::authorize("details","LeaveAutomation");
        try {
            $leave = '';
            $docs = '';
            switch (urldecode($type)){
                case ("Daily"):{
                    $leave = DailyLeave::query()->with(["automation.signs","automation.comments","days" => function($query){
                        $query->orderBy("year","ASC")->orderBy("month","ASC")->orderBy("day","ASC");},"staff"])->findOrFail($id);
                    if (Storage::disk('daily_leave_docs')->exists($id))
                        $docs = Storage::disk('daily_leave_docs')->allFiles($id);
                    break;
                }
                case ("Hourly"):{
                    $leave = HourlyLeave::query()->with(["automation.signs","automation.comments","staff","location"])->findOrFail($id);
                    if (Storage::disk('hourly_leave_docs')->exists($id))
                        $docs = Storage::disk('hourly_leave_docs')->allFiles($id);
                    break;
                }
                default:{
                    abort(404);
                }
            }
            $staff_id = $leave->staff->id;
            $leave_history = LeaveAutomation::query()->with(["automationable.staff","automationable.user"])->whereHas("automationable",
                function ($query) use($staff_id,$id){$query->where("staff_id","=",$staff_id)->where("id","<>",$id);})->get();
            return view("{$this->agent}.leave_automation_details",[
                "leave" => $leave,
                "month_names" => $this->jalali_month_names(),
                "leave_history" => $leave_history,
                "main_role" => LeaveFlow::MainRole(),
                "type" => $type,
                "docs" => $docs
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
