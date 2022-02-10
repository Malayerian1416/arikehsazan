<?php

namespace App\Http\Controllers;

use App\Models\ContractBranch;
use App\Models\ContractCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class ContractCategoryController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $contract_branches = ContractBranch::all();
            $contract_categories = ContractCategory::query()->with(["user", "branch"])->get();
            return view("desktop_dashboard.contract_category_index", ["contract_categories" => $contract_categories, "contract_branches" => $contract_branches]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            if ($request->input("category") != null && $request->input("contract_branch_id") != null) {
                ContractCategory::query()->create([
                    "category" => $request->input("category"),
                    "contract_branch_id" => $request->input("contract_branch_id"),
                    "user_id" => auth()->id()
                ]);
                DB::commit();
                return redirect()->back()->with(["result" => "saved"]);
            } else
                return redirect()->back()->with(["action_error" => "درج عنوان سرفصل پیمان و انتخاب رشته پیمان الزامی می باشد."]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            if ($request->input("category") != null && $request->input("contract_branch_id") != null) {
                $contract_category = ContractCategory::query()->findOrFail($id);
                $contract_category->update([
                    "category" => $request->input("category"),
                    "contract_branch_id" => $request->input("contract_branch_id"),
                    "user_id" => auth()->id()
                ]);
                DB::commit();
                return redirect()->back()->with(["result" => "updated"]);
            }
            else
                return redirect()->back()->with(["action_error" => "درج عنوان سرفصل پیمان و انتخاب رشته پیمان الزامی می باشد."]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $contract_category = ContractCategory::query()->findOrFail($id);
            if ($contract_category->contract()->get()->isNotEmpty()) {
                $related_contracts = "";
                foreach ($contract_category->contract()->get() as $contract)
                    $related_contracts .= "$contract->id,";
                $related_contracts = substr($related_contracts,0,-1);
                $related_contracts = "( $related_contracts )";
                return redirect()->back()->with(["action_error" => "پیمان یا پیمان های شماره $related_contracts دارای وابستگی به رکورد مورد نظر می باشد."]);
            }
            $contract_category->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
