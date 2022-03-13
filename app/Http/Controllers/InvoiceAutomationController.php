<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoicePaymentRequest;
use App\Models\BankAccount;
use App\Models\CheckPaper;
use App\Models\Contractor;
use App\Models\Invoice;
use App\Models\InvoiceAutomation;
use App\Models\InvoiceAutomationAmounts;
use App\Models\InvoiceFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Throwable;

class InvoiceAutomationController extends Controller
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
    public function get_new_items(){
        Gate::authorize("new","InvoiceAutomation");
        try {
            $invoice_automations = InvoiceAutomation::query()
                ->with(["invoice.contract.project" => function ($query) {
                    $query->whereHas("permitted_user",function ($query){$query->where("users.id","=",Auth::id());})->orderBy('id', 'asc');
                }, "invoice.contract.contractor", "invoice.user.role"])
                ->where("current_role_id", "=", Auth::user()->role->id)->where("is_finished", "<>", 1)
                ->orderBy("updated_at", "DESC")->get();
            return view("{$this->agent}.invoice_automation_new", ["invoice_automations" => $invoice_automations]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function view_details($id)
    {
        Gate::authorize("details","InvoiceAutomation");
        try {
            $invoice = Invoice::query()
                ->with([
                    "automation",
                    "user.role",
                    "contract.project",
                    "contract.contractor.banks",
                    "contract.unit","contract.category.branch",
                    "comments.user.role",
                    "signs.user.role",
                    "extras",
                    "deductions",
                    "automation_amounts.user.role"
                ])->findOrFail($id);
            $invoice->automation()->update(["is_read" => 1]);
            $main_role = InvoiceFlow::query()->where("is_main","=",1)->value("role_id");
            $main_amounts = $invoice->automation_amounts()->
            whereHas("user",function ($query) use($main_role){$query->whereHas("role",function ($q) use ($main_role){$q->where("id","=",$main_role);});})->first();
            $contractor_details = Contractor::query()->with(
                [
                    "contract.invoices.payments",
                    "contract.invoices.automation_amounts",
                    "contract.invoices.extras",
                    "contract.invoices.deductions",
                    "contract.project"
                ])->whereHas("contract.invoices")->findOrFail($invoice->contract->contractor->id);
            $contract_details = Invoice::query()->with(
                [
                    "contract.unit",
                    "payments",
                    "extras",
                    "deductions",
                    "automation_amounts.user.role",
                    "automation"
                ])->where("contract_id","=",$invoice->contract->id)->orderBy("number","asc")->get();
            $invoice_flow_permissions = InvoiceFlow::query()->where("role_id","=",Auth::user()->role->id)->first();
            $bank_accounts = BankAccount::query()->with(["docs","checks"])->get();
            $pdf = Storage::disk("invoice_pdfs")->exists("{$invoice->id}/invoicePDF.pdf");
            return view("{$this->agent}.invoice_automation_details",[
                "invoice" => $invoice,"contract_details" => $contract_details,"main_amounts" => $main_amounts,"invoice_flow_permissions" => $invoice_flow_permissions,
                "contractor_details" => $contractor_details, "bank_accounts" => $bank_accounts, "pdf" => $pdf
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function register_invoice_amounts(Request $request,$id): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $invoice = Invoice::query()->findOrFail($id);
            $invoice_flow_permissions = InvoiceFlow::query()->where("role_id","=",Auth::user()->role->id)->first();
            $main_role = InvoiceFlow::query()->where("is_main","=",1)->value("role_id");
            $duplicate = $invoice->automation_amounts()->whereHas("user",function ($query){$query->where("id","=",Auth::id());})->get();
            if ($duplicate->isNotEmpty())
                $duplicate->each->delete();
            $invoice->automation_amounts()->create([
                "user_id" => Auth::id(),
                "quantity" => $invoice_flow_permissions->quantity ? $request->input("quantity") : $invoice->automation_amounts[0]->quantity,
                "amount" => $invoice_flow_permissions->amount ? $request->input("amount") : $invoice->automation_amounts[0]->amount,
                "payment_offer" => $invoice_flow_permissions->payment_offer ? $request->input("payment_offer") : $invoice->automation_amounts[0]->payment_offer,
                "payment_offer_percent" => $invoice_flow_permissions->payment_offer ? $request->input("payment_offer_percent") : $invoice->automation_amounts[0]->payment_offer_percent,
                "is_main" => ($main_role != null && Auth::user()->role->id == $main_role) ? 1 : 0
            ]);
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function automate_sending(Request $request,$id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("send","InvoiceAutomation");
        try {
            DB::beginTransaction();
            $invoice = Invoice::query()->findOrFail($id);
            $automation = InvoiceAutomation::query()->findOrFail($invoice->automation->id);
            $current_role = InvoiceFlow::query()->where("role_id", "=", Auth::user()->role->id)->first();
            $main_role = InvoiceFlow::query()->where("is_main","=",1)->value("role_id");
            $duplicate = $invoice->automation_amounts()->whereHas("user", function ($query) {
                $query->where("id", "=", Auth::id());
            })->get();
            if ($duplicate->isEmpty()) {
                $invoice->automation_amounts()->create([
                    "user_id" => Auth::id(),
                    "quantity" => $invoice->automation_amounts[0]->quantity,
                    "amount" => $invoice->automation_amounts[0]->amount,
                    "payment_offer" => $invoice->automation_amounts[0]->payment_offer,
                    "payment_offer_percent" => $invoice->automation_amounts[0]->payment_offer_percent,
                    "is_main" => ($main_role != null && Auth::user()->role->id == $main_role) ? 1 : 0
                ]);
            }
            if ($request->input('comment') != null)
                $invoice->comments()->create(["user_id" => Auth::id(), "comment" => $request->comment]);
            $next_role = InvoiceFlow::query()->where("role_id", "=", $automation->next_role_id)->first();
            $after_next_role = 0;
            if ($next_role->is_finisher == 0) {
                $after_next_role = InvoiceFlow::query()->where("priority", "=", ++$next_role->priority)->first();
                $after_next_role = $after_next_role->role_id;
            }
            $automation->update([
                "previous_role_id" => $current_role->role_id,
                "current_role_id" => $next_role->role_id,
                "next_role_id" => $after_next_role,
                "is_read" => 0
            ]);
            if ($invoice->signs()->where("user_id","=",Auth::id())->count() == 0)
                $invoice->signs()->create(["user_id" => Auth::id(),"sign" => Auth::user()->sign]);
            DB::commit();
            return redirect()->route("InvoiceAutomation.new")->with(["result" => "sent"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function refer($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("refer","InvoiceAutomation");
        try {
            DB::beginTransaction();
            $invoice = Invoice::query()->findOrFail($id);
            $automation = InvoiceAutomation::query()->findOrFail($invoice->automation->id);
            $current_role = InvoiceFlow::query()->where("role_id", "=", Auth::user()->role->id)->first();
            if ($invoice->user->role->id == $automation->previous_role_id)
                $previous_role = InvoiceFlow::query()->where("role_id", "=", $automation->previous_role_id)->first();
            else
                $previous_role = InvoiceFlow::query()->where("role_id", "=", $automation->previous_role_id)->where("is_starter","=",0)->first();
            if($automation->previous_role_id != $automation->current_role_id) {
                $invoice->automation_amounts()->where("user_id", "=", Auth::user()->id)->delete();
                $invoice->comments()->where("user_id", "=", Auth::user()->id)->delete();
                $invoice->signs()->where("user_id", "=", Auth::user()->id)->delete();
            }
            if ($previous_role->is_starter){
                $automation->update([
                    "previous_role_id" => $previous_role->role_id,
                    "current_role_id" => 0,
                    "next_role_id" => 0,
                    "is_read" => 0
                ]);
            }
            else{
                $priority = --$current_role->priority - 1;
                $before_previous_role = InvoiceFlow::query()->where("priority", "=", $priority)->get();
                if ($before_previous_role->count() > 1 && $priority == 1)
                    $before_previous_role = $before_previous_role->where("role_id","=",$invoice->user->role->id)->first();
                else
                    $before_previous_role = $before_previous_role->first();
                $automation->update([
                    "previous_role_id" => $before_previous_role->role_id,
                    "current_role_id" => $previous_role->role_id,
                    "next_role_id" => $current_role->role_id,
                    "is_read" => 0
                ]);
            }
            DB::commit();
            return redirect()->route("InvoiceAutomation.new")->with(["result" => "referred"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function payment_process(InvoicePaymentRequest $request,$id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("pay","InvoiceAutomation");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $invoice = Invoice::query()->findOrFail($id);
            $main_amounts = InvoiceAutomationAmounts::main_amounts($invoice->id);
            $total_deduction = ($main_amounts->quantity * $main_amounts->amount) + array_sum($invoice->extras->pluck("amount")->toArray()) - array_sum($invoice->deductions->pluck("amount")->toArray());
            $automation = InvoiceAutomation::query()->findOrFail($invoice->automation->id);
            $automation->update(["previous_role_id" => $automation->current_role_id, "current_role_id" => 0, "next_role_id" => 0, "is_finished" => 1]);
            if ($request->input('comment') != null)
                $invoice->comments()->create(["user_id" => Auth::id(), "comment" => $request->comment]);
            if ($invoice->signs->where("user_id","=",Auth::id())->isEmpty())
                $invoice->signs()->create(["user_id" => Auth::id(), "sign" => Auth::user()->sign]);
            if ($request->hasFile('payment_receipt_scan')) {
                $file = $request->file('payment_receipt_scan');
                Storage::disk('invoice_payments_receipt')->put($invoice->id, $file);
            }
            $deposit_kind_string='';$deposit_kind_number='';$bank_desc='';$invoice_desc='';$pay_desc='';$bank_amount='';$pay_amount='';$bank_name='';
            switch ($validated["deposit_kind"]){
                case "cash":{
                    $deposit_kind_string = "پرداخت نقدی";
                    $deposit_kind_number = "نقد";
                    $bank_desc = "برداشت نقدی بابت وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name." و پیمانکار ".$invoice->contract->contractor->name;
                    $invoice_desc = "طلب کاری کل وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name;
                    $pay_desc = "پرداخت نقدی بابت وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name;
                    $bank_amount = -$validated["total_amount_payed"];
                    $pay_amount = -$validated["total_amount_payed"];
                    $bank_name = 'پرداخت نقدی';
                    break;
                }
                case "check":{
                    $deposit_kind_string = "پرداخت چک";
                    $deposit_kind_number = "چک";
                    $bank_desc = "برداشت با چک بابت وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name." و پیمانکار ".$invoice->contract->contractor->name;
                    $invoice_desc = "طلب کاری کل وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name;
                    $pay_desc = "پرداخت چک بابت وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name;
                    $bank_amount = 0;
                    $pay_amount = 0;
                    $bank_name = 'پرداخت چک';
                    break;
                }
                case "card":{
                    $deposit_kind_string = "پرداخت به کارت";
                    $deposit_kind_number = $request->deposit_kind_number;
                    $bank_desc = "برداشت نقدی(کارت به کارت) بابت وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name." و پیمانکار ".$invoice->contract->contractor->name;
                    $invoice_desc = "طلب کاری کل وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name;
                    $pay_desc = "پرداخت نقدی(کارت به کارت) بابت وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name;
                    $bank_amount = -$validated["total_amount_payed"];
                    $pay_amount = -$validated["total_amount_payed"];
                    $bank_name = $validated["contractor_bank"];
                    break;
                }
                case "account":{
                    $deposit_kind_string = "پرداخت به حساب";
                    $deposit_kind_number = $request->deposit_kind_number;
                    $bank_desc = "برداشت نقدی(واریز به حساب) بابت وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name." و پیمانکار ".$invoice->contract->contractor->name;
                    $invoice_desc = "طلب کاری کل وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name;
                    $pay_desc = "پرداخت نقدی(واریز به حساب) بابت وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name;
                    $bank_amount = -$validated["total_amount_payed"];
                    $pay_amount = -$validated["total_amount_payed"];
                    $bank_name = $validated["contractor_bank"];
                    break;
                }
                case "sheba":{
                    $deposit_kind_string = "پرداخت به شبا";
                    $deposit_kind_number = $request->deposit_kind_number;
                    $bank_desc = "برداشت نقدی(واریز به شبا) بابت وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name." و پیمانکار ".$invoice->contract->contractor->name;
                    $invoice_desc = "طلب کاری کل وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name;
                    $pay_desc = "پرداخت نقدی(واریز به شبا) بابت وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name;
                    $bank_amount = -$validated["total_amount_payed"];
                    $pay_amount = -$validated["total_amount_payed"];
                    $bank_name = $validated["contractor_bank"];
                    break;
                }
            }
            $bank_account = BankAccount::query()->findOrFail($validated["bank_account"]);
            $ba_id = $bank_account->docs()->create([
                "user_id" => Auth::id(),
                "description" => $bank_desc,
                "amount" => $bank_amount,
            ]);
            $contractor = Contractor::query()->findOrFail($invoice->contract->contractor->id);
            $contractor->docs()->create([
                "user_id" => Auth::id(),
                "description" => $invoice_desc,
                "amount" => $total_deduction,
            ]);
            $c_id = $contractor->docs()->create([
                "user_id" => Auth::id(),
                "description" => $pay_desc,
                "amount" => $pay_amount,
            ]);
            $ba_id->update(["doc_id" => $c_id->id]);
            $c_id->update(["doc_id" => $ba_id->id]);
            if ($validated["deposit_kind"] == "check")
                CheckPaper::query()->create([
                    "check_id" => $validated["check_id"],
                    "doc_id" => $ba_id->id,
                    "amount" => $validated["total_amount_payed"],
                    "check_number" => $validated["check_number"],
                    "receipt_date" => $validated["check_date"]
                ]);
            $invoice->payments()->create([
                "bank_name" => $bank_name,
                "amount_payed" => $validated["total_amount_payed"],
                "deposit_kind_string" => $deposit_kind_string,
                "deposit_kind_number" => $deposit_kind_number,
                "payment_receipt_number" => $validated["payment_receipt_number"],
                "receipt_scan" => $request->hasFile('payment_receipt_scan') ? 1 : 0
            ]);
            DB::commit();
            return redirect()->route("InvoiceAutomation.new")->with(["result" => "payed"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function sent_invoices(){
        Gate::authorize("sent","InvoiceAutomation");
        $invoice_automations = InvoiceAutomation::query()
            ->with(["invoice.contract.project" => function($query)
            {
                $query->whereHas("permitted_user",function ($query){$query->where("users.id","=",Auth::id());})->orderBy('id', 'asc');
            },"invoice.contract.contractor","invoice.user.role"])
            ->whereHas("invoice.signs",function ($query){$query->where("user_id","=",Auth::id());})
            ->orderBy("updated_at","DESC")->get();
        return view("{$this->agent}.invoice_automation_sent_list",["invoice_automations" => $invoice_automations]);
    }
    public function view_sent_details($id){
        try {
            $invoice = Invoice::query()
                ->with([
                    "automation",
                    "user.role",
                    "contract.project",
                    "contract.contractor.banks",
                    "contract.unit", "contract.category.branch",
                    "comments.user.role",
                    "signs.user.role",
                    "extras",
                    "deductions",
                    "automation_amounts.user.role"
                ])->findOrFail($id);
            $invoice->automation()->update(["is_read" => 1]);
            $main_role = InvoiceFlow::query()->where("is_main", "=", 1)->value("role_id");
            $main_amounts = $invoice->automation_amounts()->
            whereHas("user", function ($query) use ($main_role) {
                $query->whereHas("role", function ($q) use ($main_role) {
                    $q->where("id", "=", $main_role);
                });
            })->first();
            return view("{$this->agent}.invoice_automation_sent_details", ["invoice" => $invoice, "main_amounts" => $main_amounts]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
