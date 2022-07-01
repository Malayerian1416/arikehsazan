<?php

namespace App\Http\Controllers;

use App\Models\LeaveFlow;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class LeaveFlowController extends Controller
{
    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $leave_flow = LeaveFlow::query()->with("role")->orderBy("priority")->get();
            return view("desktop_dashboard.leave_flow_management",["leave_flow" => $leave_flow]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("adminUser");
        try {
            $roles = Role::query()->where("name","<>","ادمین")->get();
            $leave_flow = LeaveFlow::query()->with("role")->orderBy("priority")->get();
            return view("desktop_dashboard.create_edit_leave_flow",["leave_flow" => $leave_flow, "roles" => $roles]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            if ($request->has("final_inductor") && $request->input("is_main") != null) {
                LeaveFlow::query()->truncate();
                $items = [];
                $position = 1;
                foreach ($request->input("final_inductor") as $value)
                    $items[] = ["role_id" => $value, "priority" => $position++, "is_main" => $request->input("is_main") == $value?1:0];
                LeaveFlow::query()->insert($items);
                return redirect()->route("LeaveFlow.index")->with(["result" => "saved"]);

            } else
                return redirect()->back()->with(["action_error" => "برای ثبت جریان مرخصی باید حداقل یک تایید کننده و تعیین کننده نهایی انتخاب شده باشند."]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function permissions(){
        Gate::authorize("adminUser");
        try {
            $leave_flow = LeaveFlow::query()->with("role")->orderBy("priority")->get();
            return view("desktop_dashboard.leave_automation_permissions", ["leave_flow" => $leave_flow]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function set_permissions(Request $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $values = $request->except('_method', '_token');
            if ($values) {
                foreach ($values as $key => $value) {
                    $record_id = explode("@", $key);
                    $record_id = $record_id[1];
                    $columns = ["quantity", "amount", "payment_offer"];
                    $record = LeaveFlow::query()->findOrFail($record_id);
                    $record->timestamps = false;
                    foreach ($value as $permission) {
                        if (($key = array_search($permission, $columns)) !== false)
                            unset($columns[$key]);
                        $record->update([$permission => 1]);
                    }
                    foreach ($columns as $column)
                        $record->update([$column => 0]);
                }
                DB::commit();
                return redirect()->back()->with(["result" => "saved"]);
            } else
                return redirect()->back()->with(["action_error" => "اطلاعاتی انتخاب نشده است."]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
