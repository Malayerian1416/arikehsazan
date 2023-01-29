<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\BankAccount;
use App\Models\Contract;
use App\Models\ContractCategory;
use App\Models\Contractor;
use App\Models\Invoice;
use App\Models\InvoiceAutomation;
use App\Models\InvoiceDeduction;
use App\Models\InvoiceExtra;
use App\Models\Location;
use App\Models\Project;
use App\Models\Unit;
use App\Models\WorkerPaymentAutomation;
use http\Env;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class AxiosCallController extends Controller
{
    function related_data_search(Request $request): array
    {
        $response = [];
        $id = $request->input("id");
        $parent_id = $request->input("parent_id");
        switch ($request->input("type")){
            case "contract":{
                $response = Contract::query()->whereHas("category",function ($query) use ($id){$query->where("id",$id);})
                    ->whereHas("project",function ($query) use ($parent_id){$query->where("id",$parent_id);})
                    ->get(["id","name"])->flatten()->toArray();
                break;
            }
            case "contract_category":{
                if ($parent_id)
                    $response = ContractCategory::query()->whereHas("contract",function ($query) use($parent_id){$query->where("project_id","=",$parent_id);})
                        ->whereHas("branch",function ($query) use ($id){$query->where("id",$id);})->get(["id","category"])->flatten()->toArray();
                else
                    $response = ContractCategory::query()->whereHas("branch",function ($query) use ($id){$query->where("id",$id);})->get(["id","category"])->flatten()->toArray();
                break;
            }
            case "project_contract":{
                $project = Project::query()->findOrFail($id);
                $response = $project->contracts()->get(["id","name"])->toArray();
                break;
            }
            case "bank_account_information":{
                $bank_account = BankAccount::query()->findOrFail($id);
                $response = $bank_account->checks()->get(["id","sayyadi","serial"])->toArray();
                break;
            }
            case "contractor_project":{
                $response = Project::query()->whereHas("contracts.contractor",function ($query) use ($id){$query->where("contractors.id",$id);})
                    ->get(["id","name"])->flatten()->toArray();
                break;
            }
            case "contractor_project_contract":{
                if ($id != 0)
                    $response = Contract::query()->whereHas("project", function ($query) use ($id) {
                        $query->where("id", $id);
                    })->whereHas("contractor", function ($query) use ($parent_id) {$query->where("id", $parent_id);})->get(["id", "name"])->flatten()->toArray();
                else
                    $response = Contract::query()->whereHas("contractor", function ($query) use ($parent_id) {$query->where("id", $parent_id);})->get(["id", "name"])->flatten()->toArray();
                break;
            }
        }
        return $response;
    }
    function get_new_invoice_information(Request $request): array
    {
         $contract = Contract::query()->with(["category.branch","contractor","unit"])
            ->withSum(["automation_amounts" => function($query){$query->where("is_main",1);}],"quantity")
            ->withCount("invoices")->findOrFail($request->input("contract_id"))->toArray();
         $report = Invoice::report($request->input("contract_id"));
         return ["contract" => $contract,"report" => $report];
    }
    function get_invoice_details(Request $request): array
    {
        return Invoice::query()->with([
            "automation",
            "user.role",
            "contract.project",
            "contract.contractor.banks",
            "contract.unit","contract.category.branch",
            "comments.user.role",
            "signs.user.role",
            "extras",
            "deductions",
            "automation_amounts.user.role"])->findOrFail($request->input("invoice_id"))->toArray();
    }
    function live_data_adding(Request $request): array
    {
        $response = [];
        switch ($request->input("type")){
            case "new_contract_category":{
                ContractCategory::query()->create(["category" => $request->input('title'),"user_id" => auth()->id()]);
                $data = ContractCategory::all();
                foreach ($data as $item){
                    $tmp = [];
                    $tmp["id"] = $item->id;
                    $tmp["title"] = $item->category;
                    $response[] = $tmp;
                }
                break;
            }
            case "new_unit":{
                Unit::query()->create(["name" => $request->input('title'),"user_id" => auth()->id()]);
                $data = Unit::all();
                foreach ($data as $item){
                    $tmp = [];
                    $tmp["id"] = $item->id;
                    $tmp["title"] = $item->name;
                    $response[] = $tmp;
                }
                break;
            }
        }
        return $response;
    }
    function get_bank_account_information(Request $request){

    }
    function change_extra_deduction_content(Request $request){
        try {
            $desc = $request->input("desc");
            $amount = $request->input("amount");
            $id = $request->input("id");
            $type = $request->input("type");
            $action = $request->input("action");
            switch ($action){
                case "edit":{
                    switch ($type){
                        case "extra":{
                            $extra = InvoiceExtra::query()->findOrFail($id);
                            $extra->update(["description" => $desc,"amount" => $amount]);
                            break;
                        }
                        case "deduction":{
                            $deduction = InvoiceDeduction::query()->findOrFail($id);
                            $deduction->update(["description" => $desc,"amount" => $amount]);
                            break;
                        }
                    }
                    break;
                }
                case "delete":{
                    switch ($type){
                        case "extra":{
                            $extra = InvoiceExtra::query()->findOrFail($id);
                            $extra->delete();
                            break;
                        }
                        case "deduction":{
                            $deduction = InvoiceDeduction::query()->findOrFail($id);
                            $deduction->delete();
                            break;
                        }
                    }
                    break;
                }
            }
            return "done";
        }
        catch (Throwable $ex){
            return $ex;
        }
    }
    function get_new_notification(){
        $response = new StreamedResponse();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->setCallback(
            function() {
                echo "retry: 5000\n\n";
                $invoice = InvoiceAutomation::query()
                    ->where("current_role_id","=",Auth::user()->role->id)
                    ->where("is_read","=",0)->where("is_finished","=",0)->get();
                $worker = WorkerPaymentAutomation::query()
                    ->where("current_role_id","=",Auth::user()->role->id)
                    ->where("is_read","=",0)->where("is_finished","=",0)->get();
                $response = [];
                $response["new_invoice_automation"] = $invoice->count();
                $response["new_worker_payment_automation"] = $worker->count();
                $response = json_encode($response);
                echo "data: {$response}\n\n";
                ob_flush();
            });
        $response->send();
    }
    public function update_bank_information(Request $request){
        try {
            $contractor = Contractor::query()->findOrFail($request->input("contractor_id"));
            $bank = $contractor->banks->where("id", "=", $request->input("bank_id"))->first();
            $value = $request->input("value");
            switch ($request->input("type")) {
                case "card":
                {
                    $bank->update(["card" => $value]);
                    break;
                }
                case "sheba":
                {
                    $bank->update(["sheba" => $value]);
                    break;
                }
                case "account":
                {
                    $bank->update(["account" => $value]);
                    break;
                }
            }
            return "ok";
        }
        catch (Throwable $ex){
            return $ex;
        }
    }

    public function get_geo_json(Request $request){
        $location = Location::query()->findOrFail($request->input("location_id"));
        return $location->geoJson;
    }
    public function record_user_position(Request $request): string
    {
        Attendance::query()->create(["staff_id" => Auth::id(),"user_id" => Auth::id(),"location_id" => 1,"year" => 1401,"month" => 5,"day" => 1,"time" => "18:00","timestamp"=>date("Y/m/d H:i:s"),"type" => "ab"]);
        return "ok";
    }
    public function check_online(): bool
    {
        return true;
    }
    public function get_vapid_key(){
        $vapid = env("VAPID_KEY");
    }
}
