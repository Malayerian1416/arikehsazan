<?php

namespace App\Http\Controllers;

use App\Models\AbilityCategory;
use App\Models\BankAccount;
use App\Models\Contract;
use App\Models\ContractCategory;
use App\Models\InvoiceDeduction;
use App\Models\InvoiceExtra;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Request;
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
        return Contract::query()->with(["category.branch","contractor","unit"])->withCount("invoices")->findOrFail($request->input("contract_id"))->toArray();
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
                    array_push($response,$tmp);
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
                    array_push($response,$tmp);
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
}
