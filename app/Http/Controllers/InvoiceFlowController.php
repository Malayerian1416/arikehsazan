<?php

namespace App\Http\Controllers;

use App\Models\InvoiceFlow;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class InvoiceFlowController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $invoice_flow = InvoiceFlow::query()->with("role")->orderBy("priority")->get();
            return view("desktop_dashboard.invoice_flow_management",["invoice_flow" => $invoice_flow]);
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
            $invoice_flow = InvoiceFlow::query()->with("role")->orderBy("priority")->get();
            return view("desktop_dashboard.create_edit_invoice_flow",["invoice_flow" => $invoice_flow, "roles" => $roles]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            if ($request->has("final_starter") && $request->has("final_inductor") && $request->input("final_finisher") != null && $request->input("is_main") != null) {
                InvoiceFlow::query()->truncate();
                $items = [];
                $position = 2;
                foreach ($request->input("final_starter") as $starter_item)
                    $items[] = ["role_id" => $starter_item, "is_starter" => 1, "is_finisher" => 0, "priority" => 1, "is_main" => $request->input("is_main") == $starter_item?1:0];
                foreach ($request->input("final_inductor") as $inductor_item => $value)
                    $items[] = ["role_id" => $value, "is_starter" => 0, "is_finisher" => 0, "priority" => $position++, "is_main" => $request->input("is_main") == $value?1:0];
                $items[] = ["role_id" => $request->input("final_finisher"), "is_starter" => 0, "is_finisher" => 1, "priority" => $position, "is_main" => $request->input("is_main") == $request->input("final_finisher")?1:0];
                InvoiceFlow::query()->insert($items);
                return redirect()->route("InvoiceFlow.index")->with(["result" => "saved"]);

            } else
                return redirect()->back()->with(["action_error" => "برای ثبت جریان صورت وضعیت باید حداقل یک ثبت کننده، یک واسطه و یک خاتمه دهنده و تعیین کننده نهایی انتخاب شده باشند."]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function permissions(){
        Gate::authorize("adminUser");
        try {
            $invoice_flow = InvoiceFlow::query()->with("role")->orderBy("priority")->get();
            return view("desktop_dashboard.invoice_automation_permissions", ["invoice_flow" => $invoice_flow]);
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
                    $record = InvoiceFlow::query()->findOrFail($record_id);
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
