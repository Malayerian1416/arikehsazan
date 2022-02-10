<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractBranchRequest;
use App\Models\ContractBranch;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Throwable;

class ContractBranchController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $contract_branches = ContractBranch::query()->with("user")->get();
            return view("desktop_dashboard.contract_branch_index",["contract_branches" => $contract_branches]);
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
            if ($request->input("branch") != null) {
                ContractBranch::query()->create([
                    "branch" => $request->input("branch"),
                    "user_id" => auth()->id()
                ]);
                DB::commit();
                return redirect()->back()->with(["result" => "saved"]);
            } else
                return redirect()->back()->with(["action_error" => "درج عنوان رشته پیمان الزامی می باشد."]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(ContractBranchRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            if ($request->input("branch") != null) {
                $contract_branch = ContractBranch::query()->findOrFail($id);
                $contract_branch->update([
                    "branch" => $request->input("branch"),
                    "user_id" => auth()->id()
                ]);
                DB::commit();
                return redirect()->back()->with(["result" => "updated"]);
            }
            else
                return redirect()->back()->with(["action_error" => "درج عنوان رشته پیمان الزامی می باشد."]);
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
            $contract_branch = ContractBranch::query()->findOrFail($id);
            if ($contract_branch->categories()->get()->isNotEmpty()) {
                $related_categories = "";
                foreach ($contract_branch->categories()->get() as $category)
                    $related_categories .= "$category->id,";
                $related_categories = substr($related_categories,0,-1);
                $related_categories = "( $related_categories )";
                return redirect()->back()->with(["action_error" => "سرفصل پیمان(های) شماره $related_categories دارای وابستگی به رکورد مورد نظر می باشد."]);
            }
            $contract_branch->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
