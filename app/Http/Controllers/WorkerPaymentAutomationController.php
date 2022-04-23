<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkerPaymenProcesstRequest;
use App\Http\Requests\WorkerPaymentRequest;
use App\Models\BankAccount;
use App\Models\CheckPaper;
use App\Models\Contractor;
use App\Models\Invoice;
use App\Models\InvoiceAutomation;
use App\Models\InvoiceAutomationAmounts;
use App\Models\InvoiceFlow;
use App\Models\Project;
use App\Models\WorkerPaymentAutomation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Throwable;

class WorkerPaymentAutomationController extends Controller
{
    public function __construct()
    {
        $agent = new Agent();
        if ($agent->isDesktop())
            $this->agent = "desktop_dashboard";
        else if($agent->isPhone())
            $this->agent = "phone_dashboard";
        else if ($agent->robot())
            return view("errors/cant_detect_device");
        else
            return view("errors/cant_detect_device");
        return false;
    }
    public function index(){
        Gate::authorize("index","WorkerPayments");
        try {
            $projects = Project::get_permissions([]);
            $workers = Contractor::query()->where("type","=",1)->get();
            $worker_automations = WorkerPaymentAutomation::query()->with(["project","contractor","user"])
                ->whereHas("user",function ($query){$query->where("id","=",Auth::id());})->get();
            return view("{$this->agent}.worker_automations",["worker_automations" => $worker_automations,"projects" => $projects,"workers" => $workers]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function create(){
        Gate::authorize("create","WorkerPayments");
        try {
            $projects = Project::get_permissions([]);
            $workers = Contractor::query()->where("type","=",1)->get();
            return view("{$this->agent}.create_new_worker_payment",["projects" => $projects,"workers" => $workers]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function store(WorkerPaymentRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","WorkerPayments");
        $validated = $request->validated();
        try {
            DB::beginTransaction();
            $invoice_flow = InvoiceFlow::all();
            $validated["previous_role_id"] = Auth::user()->role->id;
            $current_role_id = $invoice_flow->where("priority","=",2)->first()->role_id;
            $validated["current_role_id"] = $current_role_id ?: 0;
            $next_role_id = $invoice_flow->where("priority","=",3)->first()->role_id;
            $validated["next_role_id"] = $next_role_id ?: 0;
            $validated["user_id"] = Auth::id();
            $worker_automation = WorkerPaymentAutomation::query()->create($validated);
            $worker_automation->signs()->create(["user_id" => Auth::id(),"sign" => Auth::user()->sign]);
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function edit($id){
        Gate::authorize("edit","WorkerPayments");
        try {
            $projects = Project::get_permissions();
            $workers = Contractor::query()->where("type","=",1)->get();
            $worker_automation = WorkerPaymentAutomation::query()->with(["project","contractor"])->findOrFail($id);
            return view("{$this->agent}.edit_created_worker_automation",["worker_automation" => $worker_automation,"projects" => $projects,"workers" => $workers]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function update(WorkerPaymentRequest $request,$id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","WorkerPayments");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $worker_automation = WorkerPaymentAutomation::query()->findOrFail($id);
            $worker_automation->update($validated);
            $worker_automation->signs()->where("user_id","=",Auth::id())->first()->touch();
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
        Gate::authorize("destroy","WorkerPayments");
        try {
            DB::beginTransaction();
            $worker_automation = WorkerPaymentAutomation::query()->findOrFail($id);
            $worker_automation->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function get_new_items(){
        Gate::authorize("new","WorkerPayments");
        try {
            $worker_payments = WorkerPaymentAutomation::query()->with([
                "project" => function($query){$query->whereHas("permitted_user",function ($query){$query->where("users.id","=",Auth::id());});},"contractor","user","signs"])
                ->where("current_role_id", "=", Auth::user()->role->id)->where("is_finished", "<>", 1)
                ->orderBy("updated_at", "DESC")->get();
            WorkerPaymentAutomation::query()->whereHas("project.permitted_user",function ($query){$query->where("users.id","=",Auth::id());})
                ->where("current_role_id", "=", Auth::user()->role->id)->where("is_finished", "<>", 1)
                ->update(["is_read" => 1]);
            return view("{$this->agent}.worker_automation_new",["worker_payments" => $worker_payments]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function automate_sending($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("send","WorkerPayments");
        try {
            DB::beginTransaction();
            $worker_automation = WorkerPaymentAutomation::query()->findOrFail($id);
            $invoice_automation = InvoiceFlow::automate();
            $worker_automation->update($invoice_automation);
            if ($worker_automation->signs->where("user_id","=",Auth::id())->isEmpty())
                $worker_automation->signs()->create(["user_id" => Auth::id(),"sign" => Auth::user()->sign]);
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function payment($id){
        Gate::authorize("pay","WorkerPayments");
        try {
            $bank_accounts = BankAccount::query()->with(["docs","checks"])->get();
            $worker_automation = WorkerPaymentAutomation::query()->with(["contractor.banks","user","signs"])->findOrFail($id);
            return view("{$this->agent}.worker_payment_process",["bank_accounts" => $bank_accounts,"worker_automation" => $worker_automation]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function payment_process(WorkerPaymenProcesstRequest $request,$id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("pay","WorkerPayments");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $worker_automation = WorkerPaymentAutomation::query()->findOrFail($id);
            $worker_automation->update(["previous_role_id" => $worker_automation->current_role_id, "current_role_id" => 0, "next_role_id" => 0, "is_finished" => 1]);
            if ($worker_automation->signs->where("user_id","=",Auth::id())->isEmpty())
                $worker_automation->signs()->create(["user_id" => Auth::id(), "sign" => Auth::user()->sign]);
            if ($request->hasFile('payment_receipt_scan')) {
                $file = $request->file('payment_receipt_scan');
                Storage::disk('worker_payments_receipt')->put($worker_automation->id, $file);
            }
            $deposit_kind_string='';$deposit_kind_number='';$bank_desc='';$worker_desc='';$pay_desc='';$bank_amount='';$pay_amount='';$bank_name='';
            switch ($validated["deposit_kind"]){
                case "cash":{
                    $deposit_kind_string = "پرداخت نقدی";
                    $deposit_kind_number = "نقد";
                    $bank_desc = "برداشت نقدی بابت اتوماسیون پرداختی کارگری شماره ".$worker_automation->هid." کارگر ".$worker_automation->contractor->name;
                    $worker_desc = "طلب کاری کل اتوماسیون پرداختی کارگری شماره ".$worker_automation->number;
                    $pay_desc = "پرداخت نقدی بابت اتوماسیون پرداختی کارگری شماره ".$worker_automation->number;
                    $bank_amount = -$worker_automation->amount;
                    $pay_amount = -$worker_automation->amount;
                    $bank_name = 'پرداخت نقدی';
                    break;
                }
                case "check":{
                    $deposit_kind_string = "پرداخت چک";
                    $deposit_kind_number = "چک";
                    $bank_desc = "برداشت با چک بابت اتوماسیون پرداختی کارگری شماره ".$worker_automation->هid." کارگر ".$worker_automation->contractor->name;
                    $worker_desc = "طلب کاری کل اتوماسیون پرداختی کارگری شماره ".$worker_automation->number;
                    $pay_desc = "پرداخت چک بابت اتوماسیون پرداختی کارگری شماره ".$worker_automation->number;
                    $bank_amount = 0;
                    $pay_amount = 0;
                    $bank_name = 'پرداخت چک';
                    break;
                }
                case "card":{
                    $deposit_kind_string = "پرداخت به کارت";
                    $deposit_kind_number = $request->deposit_kind_number;
                    $bank_desc = "برداشت نقدی(کارت به کارت) بابت اتوماسیون پرداختی کارگری شماره ".$worker_automation->هid." کارگر ".$worker_automation->contractor->name;
                    $worker_desc = "طلب کاری کل اتوماسیون پرداختی کارگری شماره ".$worker_automation->number;
                    $pay_desc = "پرداخت نقدی(کارت به کارت) بابت اتوماسیون پرداختی کارگری شماره ".$worker_automation->number;
                    $bank_amount = -$worker_automation->amount;
                    $pay_amount = -$worker_automation->amount;
                    $bank_name = $validated["contractor_bank"];
                    break;
                }
                case "account":{
                    $deposit_kind_string = "پرداخت به حساب";
                    $deposit_kind_number = $request->deposit_kind_number;
                    $bank_desc = "برداشت نقدی(واریز به حساب) بابت اتوماسیون پرداختی کارگری شماره ".$worker_automation->هid." کارگر ".$worker_automation->contractor->name;
                    $worker_desc = "طلب کاری کل اتوماسیون پرداختی کارگری شماره ".$worker_automation->number;
                    $pay_desc = "پرداخت نقدی(واریز به حساب) بابت اتوماسیون پرداختی کارگری شماره ".$worker_automation->number;
                    $bank_amount = -$worker_automation->amount;
                    $pay_amount = -$worker_automation->amount;
                    $bank_name = $validated["contractor_bank"];
                    break;
                }
                case "sheba":{
                    $deposit_kind_string = "پرداخت به شبا";
                    $deposit_kind_number = $request->deposit_kind_number;
                    $bank_desc = "برداشت نقدی(واریز به شبا) بابت اتوماسیون پرداختی کارگری شماره ".$worker_automation->هid." کارگر ".$worker_automation->contractor->name;
                    $worker_desc = "طلب کاری کل اتوماسیون پرداختی کارگری شماره ".$worker_automation->number;
                    $pay_desc = "پرداخت نقدی(واریز به شبا) بابت اتوماسیون پرداختی کارگری شماره ".$worker_automation->number;
                    $bank_amount = -$worker_automation->amount;
                    $pay_amount = -$worker_automation->amount;
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
            $contractor = Contractor::query()->findOrFail($worker_automation->contractor->id);
            $contractor->docs()->create([
                "user_id" => Auth::id(),
                "description" => $worker_desc,
                "amount" => $worker_automation->amount,
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
                    "amount" => $worker_automation->amount,
                    "check_number" => $validated["check_number"],
                    "receipt_date" => $validated["check_date"]
                ]);
            $worker_automation->payments()->create([
                "bank_name" => $bank_name,
                "amount_payed" => $worker_automation->amount,
                "deposit_kind_string" => $deposit_kind_string,
                "deposit_kind_number" => $deposit_kind_number,
                "payment_receipt_number" => $validated["payment_receipt_number"],
                "receipt_scan" => $request->hasFile('payment_receipt_scan') ? 1 : 0
            ]);
            DB::commit();
            return redirect()->route("WorkerPayments.new")->with(["result" => "payed","print" => $worker_automation->id]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function sent_worker_payments(){
        Gate::authorize("sent","WorkerPayments");
        try {
            $worker_payments = WorkerPaymentAutomation::query()->with([
                "project","contractor","user","payments"])
                ->whereHas("signs", function ($query){$query->where("user_id","=",Auth::id());})
                ->orderBy("updated_at", "DESC")->get();
            return view("{$this->agent}.worker_automation_sent_list",["worker_payments" => $worker_payments]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function print_payment($id){
        $worker_automation = WorkerPaymentAutomation::query()->with(["project","contractor","signs","payments"])->findOrFail($id);
        return view("{$this->agent}.print_worker_payment",["worker_automation" => $worker_automation]);
    }
}
