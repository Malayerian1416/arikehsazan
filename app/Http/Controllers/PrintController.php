<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Contractor;
use App\Models\Invoice;
use App\Models\InvoiceFlow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PrintController extends Controller
{
    public function print_invoice($id)
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
            return view("desktop_dashboard.print_invoice",[
                "invoice" => $invoice,"contract_details" => $contract_details,"main_amounts" => $main_amounts,"invoice_flow_permissions" => $invoice_flow_permissions,
                "contractor_details" => $contractor_details, "bank_accounts" => $bank_accounts
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
