<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\BankAccount;
use App\Models\Contractor;
use App\Models\Invoice;
use App\Models\InvoiceFlow;
use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
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
    public function print_salary_report(Request $request)
    {
        Gate::authorize("salary_reports_make","Reports");
        try {
            $totals = [];
            $validated = $request->toArray();
            $holidays = $request->has("holidays") ? $validated["holidays"] : [];
            $staff = User::query()->findOrFail($validated["staff_id"]);
            $results = Attendance::get_working_days($staff->id, $this->get_gregorian_timestamp($validated["from_date"]), $this->get_gregorian_timestamp($validated["to_date"]), $validated["work_shift_id"], $holidays);
            if ($results){
                $total_wage = 0;
                $total_days = count($results);
                $total_Presence_day = 0;
                $total_holidays = 0;
                $total_absence_day = 0;
                $total_delay = 0;
                $total_acceleration = 0;
                $total_leaves = 0;
                $total_overtime_work = 0;
                $total_absence = 0;
                $counter = 0;
                $total_absence_day_illegal = 0;
                foreach ($results as $result) {
                    if ($result["status"] == 0) {
                        $total_wage += $result["daily_wage"];
                        switch ($result["attendance"]) {
                            case "حاضر":
                            {
                                $total_Presence_day++;
                                break;
                            }
                            case "بدون شیفت":
                            {
                                $total_holidays++;
                                break;
                            }
                            case "مرخصی":
                            {
                                $total_leaves++;
                                break;
                            }
                            case "غایب":
                            {
                                $total_absence_day++;
                                break;
                            }
                            case "غایب(سقف مرخصی)":
                            {
                                $total_absence_day_illegal++;
                                break;
                            }
                        }
                        $total_overtime_work += $result["overtime_work_amount"] + $result["free_overtime_work_amount"];
                        $total_delay += $result["delay_amount"];
                        $total_acceleration += $result["acceleration_amount"];
                        $total_absence += $result["absence_amount"];
                        $counter++;
                    }
                }
                $total_payable = ($total_wage + $total_overtime_work) - ($total_delay + $total_acceleration + $total_absence);
                $totals = [
                    "total_wage" => number_format($total_wage),
                    "total_days" => $total_days,
                    "total_Presence_day" => $total_Presence_day,
                    "total_holidays" => $total_holidays,
                    "total_absence_day" => $total_absence_day,
                    "total_delay" => number_format($total_delay),
                    "total_acceleration" => number_format($total_acceleration),
                    "total_leaves" => $total_leaves,
                    "total_absence_day_illegal" => $total_absence_day_illegal,
                    "total_overtime_work" => number_format($total_overtime_work),
                    "total_absence" => number_format($total_absence),
                    "total_payable" => number_format($total_payable)
                ];
            }
            return view("{$this->agent}.print_salary_report", [
                "results" => $results,
                "totals" => $totals,
                "date_range" => $validated["from_date"]." تا ".$validated["to_date"],
                "staff_name" => $staff->name
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
