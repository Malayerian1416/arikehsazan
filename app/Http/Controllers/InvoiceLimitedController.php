<?php

namespace App\Http\Controllers;

use App\Events\InvoiceEvent;
use App\Events\NewInvoiceAutomation;
use App\Http\Requests\InvoiceLimitedRequest;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\InvoiceAutomationAmounts;
use App\Models\InvoiceComment;
use App\Models\InvoiceFlow;
use App\Models\Project;
use App\Models\User;
use App\Notifications\PushMessageInvoice;
use App\Notifications\PushNewInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Throwable;

class InvoiceLimitedController extends Controller
{
    public function index()
    {
        Gate::authorize("index","InvoicesLimited");
        try {
            $contracts = Contract::query()->with(["project","contractor"])->get();
            $invoices = Invoice::query()->with(["contract.project","contract.contractor","payments","automation"])->where("user_id",Auth::id())->latest()->get();
            $projects = Project::get_permissions([]);
            return view("{$this->agent}.created_invoices_index_limited",["invoices" => $invoices, "contracts" => $contracts,"projects" => $projects]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("create","InvoicesLimited");
        try {
            $projects = Project::get_permissions([]);
            return view("{$this->agent}.create_new_invoice_limited",["projects" => $projects]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(InvoiceLimitedRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","InvoicesLimited");
        try {
            DB::beginTransaction();
            $ex_total = 0;
            $de_total = 0;
            $validated = $request->validated();
            $invoice = Invoice::query()->create([
                "number" => $validated["invoice_number"],
                "contract_id" => $validated["contract_id"],
                "user_id" => Auth::id(),
                "is_final" => $request->has("final_invoice") ? 1 : 0
            ]);
            $automation_amount = $invoice->automation_amounts()->create([
                "user_id" => Auth::id(),
                "quantity" => $validated["quantity"],
                "amount" => $invoice->contract->amount,
                "payment_offer" => 0,
                "payment_offer_percent" => $validated["payment_offer_percent"]
            ]);
            if ($request->has("extra_work_desc")){
                $counter = 0;
                foreach ($validated["extra_work_desc"] as $extra_desc){
                    $extra_amount = $validated["extra_work_amount"][$counter++];
                    $extra_desc = $extra_desc ?: 'اضافه کار(بدون شرح)';
                    $extra_amount = $extra_amount ?: 0;
                    $ex_total += $extra_amount;
                    $invoice->extras()->create(["user_id" => Auth::id(),"description" => $extra_desc,"amount" => $extra_amount]);
                }
            }
            if ($request->has("deduction_work_desc")){
                $counter = 0;
                foreach ($validated["deduction_work_desc"] as $deduction_desc){
                    $deduction_amount = $validated["deduction_work_amount"][$counter++];
                    $deduction_desc = $deduction_desc ?: 'کسرکار(بدون شرح)';
                    $deduction_amount = $deduction_amount ?: 0;
                    $de_total += $deduction_amount;
                    $invoice->deductions()->create(["user_id" => Auth::id(),"description" => $deduction_desc,"amount" => $deduction_amount]);
                }
            }
            $total_amount = ($validated["quantity"] * $invoice->contract->amount) + ($ex_total - $de_total);
            $payment_offer = ($validated["payment_offer_percent"] / 100) * $total_amount;
            $automation_amount->update(["payment_offer" => $payment_offer]);
            if ($request->input('comment') != null)
                $invoice->comments()->create(["user_id" => Auth::id(),"comment" => $validated["comment"]]);
            $invoice->signs()->create(["user_id" => Auth::id(),"sign" => Auth::user()->sign]);
            $invoice_flow = InvoiceFlow::all();
            $current_role_id = $invoice_flow->where("priority","=",2)->first()->role_id;
            $current_role_id = $current_role_id ?: 0;
            $next_role_id = $invoice_flow->where("priority","=",3)->first()->role_id;
            $next_role_id = $next_role_id ?: 0;
            $invoice_automation = ["previous_role_id" => Auth::user()->role->id,"current_role_id" => $current_role_id,"next_role_id" => $next_role_id];
            $invoice->automation()->create($invoice_automation);
            if ($request->hasFile('invoice_images')){
                foreach ($request->file('invoice_images') as $file)
                    Storage::disk('invoice_images')->put($invoice->id,$file);
            }
            DB::commit();
            $message = "درخواست پرداخت وضعیت جدید پیمانکاری به اتوماسیون شما ارسال شده است";
            $this->send_push_notification(PushMessageInvoice::class,$message,"role_id",$invoice->automation->current_role_id);
            $this->send_event_notification(InvoiceEvent::class,$invoice->automation,$message);
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("edit","InvoicesLimited");
        try {
            $invoice = Invoice::query()->with([
                "contract.category.branch",
                "contract.project",
                "contract.unit",
                "contract.contractor",
                "extras","deductions",
                "comments" => function($query){$query->where("user_id","=",Auth::id());},
                "automation_amounts" => function($query){$query->where("user_id","=",Auth::id());}])->findOrFail($id);
            return view("{$this->agent}.edit_created_invoice_limited",["invoice" => $invoice]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(InvoiceLimitedRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","InvoicesLimited");
        try {
            DB::beginTransaction();
            $ex_total = 0;
            $de_total = 0;
            $validated = $request->validated();
            $invoice = Invoice::query()->findOrFail($id);
            $invoice->update(["is_final" => $request->has("final_invoice") ? 1 : 0]);
            $automation_amount = InvoiceAutomationAmounts::query()->findOrFail($request->input("automation_amount_id"));
            if ($request->has("extra_work_desc")){
                $counter = 0;
                foreach ($validated["extra_work_desc"] as $extra_desc){
                    $extra_amount = $validated["extra_work_amount"][$counter++];
                    $extra_desc = $extra_desc ?: 'اضافه کار(بدون شرح)';
                    $extra_amount = $extra_amount ?: 0;
                    $ex_total += $extra_amount;
                    $invoice->extras()->create(["user_id" => Auth::id(),"description" => $extra_desc,"amount" => $extra_amount]);
                }
            }
            if ($request->has("deduction_work_desc")){
                $counter = 0;
                foreach ($validated["deduction_work_desc"] as $deduction_desc){
                    $deduction_amount = $validated["deduction_work_amount"][$counter++];
                    $deduction_desc = $deduction_desc ?: 'کسرکار(بدون شرح)';
                    $deduction_amount = $deduction_amount ?: 0;
                    $de_total += $deduction_amount;
                    $invoice->deductions()->create(["user_id" => Auth::id(),"description" => $deduction_desc,"amount" => $deduction_amount]);
                }
            }
            $total_amount = ($validated["quantity"] * $invoice->contract->amount) + ($ex_total - $de_total) + ($invoice->extras->sum("amount") - $invoice->deductions->sum("amount"));
            $payment_offer = ($validated["payment_offer_percent"] / 100) * $total_amount;
            $automation_amount->update([
                "quantity" => $validated["quantity"],
                "payment_offer_percent" => $validated["payment_offer_percent"],
                "payment_offer" => $payment_offer
            ]);;
            if ($request->input("invoice_comment_id") === null && $validated["comment"] != null)
                $invoice->comments()->create(["user_id" => Auth::id(),"comment" => $validated["comment"]]);
            elseif($request->input("invoice_comment_id") != null && $validated["comment"] != null)
                InvoiceComment::query()->findOrFail($request->input("invoice_comment_id"))->update(["comment" => $validated["comment"]]);
            $invoice_flow = InvoiceFlow::all();
            $current_role_id = $invoice_flow->where("priority","=",2)->first()->role_id;
            $current_role_id = $current_role_id ?: 0;
            $next_role_id = $invoice_flow->where("priority","=",3)->first()->role_id;
            $next_role_id = $next_role_id ?: 0;
            $invoice_automation = ["previous_role_id" => Auth::user()->role->id,"current_role_id" => $current_role_id,"next_role_id" => $next_role_id, "is_read" => 0];
            $invoice->automation()->update($invoice_automation);
            DB::commit();
            $message = "درخواست پرداخت وضعیت جدید پیمانکاری پس از ویرایش به اتوماسیون شما ارسال شده است";
            $this->send_push_notification(PushMessageInvoice::class,$message,"role_id",$invoice->automation->current_role_id);
            $this->send_event_notification(InvoiceEvent::class,$invoice->automation,$message);
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("destroy","InvoicesLimited");
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
