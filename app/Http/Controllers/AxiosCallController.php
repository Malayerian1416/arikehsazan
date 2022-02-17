<?php

namespace App\Http\Controllers;

use App\Models\AbilityCategory;
use App\Models\BankAccount;
use App\Models\Contract;
use App\Models\ContractCategory;
use App\Models\InvoiceAutomation;
use App\Models\InvoiceDeduction;
use App\Models\InvoiceExtra;
use App\Models\Project;
use App\Models\Unit;
use App\Models\WorkerPaymentAutomation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class AxiosCallController extends Controller
{
    function related_data_search(Request $request): array
    {
        $response = [];
        $id = $request->input("id");
        switch ($request->input("type")){
            case "contract_category":{
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
        }
        return $response;
    }
    function get_new_invoice_information(Request $request): array
    {
        return Contract::query()->with(["category.branch","contractor","unit"])
            ->withSum(["automation_amounts" => function($query){$query->where("is_main",1);}],"quantity")
            ->withCount("invoices")->findOrFail($request->input("contract_id"))->toArray();
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
}
