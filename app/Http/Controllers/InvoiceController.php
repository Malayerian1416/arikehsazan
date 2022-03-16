<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\InvoiceAutomationAmounts;
use App\Models\InvoiceComment;
use App\Models\InvoiceFlow;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Jenssegers\Agent\Agent;
use Throwable;

class InvoiceController extends Controller
{
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
    public function index()
    {
        Gate::authorize("index","Invoices");
        try {
            $contracts = Contract::get_permissions(["project","contractor"]);
            $invoices = Invoice::query()->with(["contract.project","contract.contractor","payments","automation"])->where("user_id",Auth::id())->latest()->get();
            return view("{$this->agent}.created_invoices_index",["invoices" => $invoices, "contracts" => $contracts]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("create","Invoices");
        try {
            $projects = Project::get_permissions([]);
            return view("{$this->agent}.create_new_invoice",["projects" => $projects]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(InvoiceRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","Invoices");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $invoice = Invoice::query()->create([
                "number" => $validated["invoice_number"],
                "contract_id" => $validated["contract_id"],
                "user_id" => Auth::id(),
                "is_final" => $request->has("final_invoice") ? 1 : 0
            ]);
            $invoice->automation_amounts()->create([
                "user_id" => Auth::id(),
                "quantity" => $validated["quantity"],
                "amount" => $validated["amount"],
                "payment_offer" => $validated["payment_offer"],
                "payment_offer_percent" => $validated["payment_offer_percent"]
            ]);
            if ($request->has("extra_work_desc")){
                $counter = 0;
                foreach ($validated["extra_work_desc"] as $extra_desc){
                    $extra_amount = $validated["extra_work_amount"][$counter++];
                    $extra_desc = $extra_desc ?: 'اضافه کار(بدون شرح)';
                    $extra_amount = $extra_amount ?: 0;
                    $invoice->extras()->create(["user_id" => Auth::id(),"description" => $extra_desc,"amount" => $extra_amount]);
                }
            }
            if ($request->has("deduction_work_desc")){
                $counter = 0;
                foreach ($validated["deduction_work_desc"] as $deduction_desc){
                    $deduction_amount = $validated["deduction_work_amount"][$counter++];
                    $deduction_desc = $deduction_desc ?: 'کسرکار(بدون شرح)';
                    $deduction_amount = $deduction_amount ?: 0;
                    $invoice->deductions()->create(["user_id" => Auth::id(),"description" => $deduction_desc,"amount" => $deduction_amount]);
                }
            }
            if ($request->input('comment') != null)
                $invoice->comments()->create(["user_id" => Auth::id(),"comment" => $validated["comment"]]);
            $invoice->signs()->create(["user_id" => Auth::id(),"sign" => Auth::user()->sign]);
            $invoice_automation = InvoiceFlow::automate();
            $invoice->automation()->create($invoice_automation);
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("edit","Invoices");
        try {
            $invoice = Invoice::query()->with([
                "contract.category.branch",
                "contract.project",
                "contract.unit",
                "contract.contractor",
                "extras","deductions",
                "comments" => function($query){$query->where("user_id","=",Auth::id());},
                "automation_amounts" => function($query){$query->where("user_id","=",Auth::id());}])->findOrFail($id);
            return view("{$this->agent}.edit_created_invoice",["invoice" => $invoice]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(InvoiceRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","Invoices");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $invoice = Invoice::query()->findOrFail($id);
            $invoice->update(["is_final" => $request->has("final_invoice") ? 1 : 0]);
            InvoiceAutomationAmounts::query()->findOrFail($request->input("automation_amount_id"))->update([
                "quantity" => $validated["quantity"],
                "amount" => $validated["amount"],
                "payment_offer" => $validated["payment_offer"],
                "payment_offer_percent" => $validated["payment_offer_percent"]
            ]);
            if ($request->has("extra_work_desc")){
                $counter = 0;
                foreach ($validated["extra_work_desc"] as $extra_desc){
                    $extra_amount = $validated["extra_work_amount"][$counter++];
                    $extra_desc = $extra_desc ?: 'اضافه کار(بدون شرح)';
                    $extra_amount = $extra_amount ?: 0;
                    $invoice->extras()->create(["user_id" => Auth::id(),"description" => $extra_desc,"amount" => $extra_amount]);
                }
            }
            if ($request->has("deduction_work_desc")){
                $counter = 0;
                foreach ($validated["deduction_work_desc"] as $deduction_desc){
                    $deduction_amount = $validated["deduction_work_amount"][$counter++];
                    $deduction_desc = $deduction_desc ?: 'کسرکار(بدون شرح)';
                    $deduction_amount = $deduction_amount ?: 0;
                    $invoice->deductions()->create(["user_id" => Auth::id(),"description" => $deduction_desc,"amount" => $deduction_amount]);
                }
            }
            if ($request->input("invoice_comment_id") === null && $validated["comment"] != null)
                $invoice->comments()->create(["user_id" => Auth::id(),"comment" => $validated["comment"]]);
            elseif($request->input("invoice_comment_id") != null && $validated["comment"] != null)
                InvoiceComment::query()->findOrFail($request->input("invoice_comment_id"))->update(["comment" => $validated["comment"]]);
            $invoice_automation = InvoiceFlow::automate();
            $invoice->automation()->update($invoice_automation);
            DB::commit();
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("destroy","Invoices");
        try {
            DB::beginTransaction();
            Invoice::query()->findOrFail($id)->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
