<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractRequest;
use App\Models\Contract;
use App\Models\ContractBranch;
use App\Models\Contractor;
use App\Models\Project;
use App\Models\Unit;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Throwable;
use ZipArchive;

class ContractController extends Controller
{
    public function index(Request $request){
        Gate::authorize("index","Contracts");
        try {
            $contracts = '';
            if ($request->has('search_request')) {
                switch ($request->input("search_option")[0]) {
                    case "project":
                    {
                        $project_id = $request->input('project_id');
                        $contracts = Contract::query()->with(["contractor", "user", "project"])->whereHas("project",
                            function ($query) use ($project_id) {
                                $query->where("id", $project_id);
                            })
                            ->orderBy("created_at", $request->input('date_sort'))->get();
                        break;
                    }
                    case "contractor":
                    {
                        $contractor_id = $request->input('contractor_id');
                        $contracts = Contract::query()->with(["contractor", "user", "project"])->whereHas("contractor",
                            function ($query) use ($contractor_id) {
                                $query->where("id", $contractor_id);
                            })
                            ->orderBy("created_at", $request->input('date_sort'))->get();
                        break;
                    }
                }
            } else
                $contracts = Contract::get_permissions(["contractor", "user", "unit"]);
            $contractors = Contractor::all();
            $projects = Project::get_permissions([]);
            $contract_branches = ContractBranch::all();
            $units = Unit::all();
            $docs = [];
            foreach ($contracts as $contract) {
                if (Storage::disk('contracts_doc')->exists("$contract->id"))
                    $docs[] = $contract->id;
            }
            return view("{$this->agent}.contract_index", [
                "contracts" => $contracts,
                "docs" => $docs,
                "contractors" => $contractors,
                "projects" => $projects,
                "contract_branches" => $contract_branches,
                "units" => $units,
                "contract_row" => $this->contract_number()
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("create","Contracts");
        try {
            $projects = Project::all();
            $contract_branches = ContractBranch::all();
            $contractors = Contractor::all();
            $units = Unit::all();
            return view("{$this->agent}.create_new_contract",
                [
                    "projects" => $projects,
                    "contract_branches" => $contract_branches,
                    "contractors" => $contractors,
                    "units" => $units
                ]
            );
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(ContractRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","Contracts");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            unset($validated["contract_branch_id"]);
            $validated["amount"] = str_replace(',','',$validated["amount"]);
            $date_of_contract = explode("/",$validated["date_of_contract"]);
            $validated["date_of_contract"] = implode("-",Verta::getGregorian($date_of_contract[0],$date_of_contract[1],$date_of_contract[2]));
            $contract_start_date = explode("/",$validated["contract_start_date"]);
            $validated["contract_start_date"] = implode("-",Verta::getGregorian($contract_start_date[0],$contract_start_date[1],$contract_start_date[2]));
            $contract_completion_date = explode("/",$validated["contract_completion_date"]);
            $validated["contract_completion_date"] = implode("-",Verta::getGregorian($contract_completion_date[0],$contract_completion_date[1],$contract_completion_date[2]));
            $validated["user_id"] = auth()->id();
            $contract = Contract::query()->create($validated);
            if ($request->hasFile('agreement_sample')){
                foreach ($request->file('agreement_sample') as $file)
                    Storage::disk('contracts_doc')->put($contract->id,$file);
            }
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("edit","Contracts");
        try {
            $docs = null;
            $contract = Contract::query()->with(["project", "contractor", "unit", "category.branch"])->findOrFail($id);
            $projects = Project::all();
            $contract_branches = ContractBranch::query()->with("categories")->get();
            $contractors = Contractor::all();
            $units = Unit::all();
            if (Storage::disk('contracts_doc')->exists("$id"))
                $docs = Storage::disk('contracts_doc')->allFiles("$id");
            return view("{$this->agent}.edit_contract",
                [
                    "contract" => $contract,
                    "projects" => $projects,
                    "contract_branches" => $contract_branches,
                    "contractors" => $contractors,
                    "units" => $units,
                    "docs" => $docs
                ]
            );
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(ContractRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","Contracts");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["amount"] = str_replace(",",'',$validated["amount"]);
            if ($request->has("contract_category_id") and $request->input("contract_category_id") != null and $request->input("contract_category_id") != 0)
                $validated["contract_category_id"] = $request->input("contract_category_id");
            else
                $validated["contract_category_id"] = null;
            $date_of_contract = explode("/",$validated["date_of_contract"]);
            $validated["date_of_contract"] = implode("-",Verta::getGregorian($date_of_contract[0],$date_of_contract[1],$date_of_contract[2]));
            $contract_start_date = explode("/",$validated["contract_start_date"]);
            $validated["contract_start_date"] = implode("-",Verta::getGregorian($contract_start_date[0],$contract_start_date[1],$contract_start_date[2]));
            $contract_completion_date = explode("/",$validated["contract_completion_date"]);
            $validated["contract_completion_date"] = implode("-",Verta::getGregorian($contract_completion_date[0],$contract_completion_date[1],$contract_completion_date[2]));
            $validated["user_id"] = auth()->id();
            $contract = Contract::query()->findOrFail($id);
            $contract->update($validated);
            if ($request->hasFile('agreement_sample')){
                if (Storage::disk("contracts_doc")->exists("zip/{$id}"))
                    Storage::disk("contracts_doc")->deleteDirectory("zip/{$id}");
                foreach ($request->file('agreement_sample') as $file)
                    Storage::disk('contracts_doc')->put($id,$file);
            }
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
        Gate::authorize("destroy","Contracts");
        try {
            DB::beginTransaction();
            $contract = Contract::query()->findOrFail($id);
            $contract->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function contract_change_activation($id): \Illuminate\Http\RedirectResponse
    {
        $result = Contract::query()->find($id)->change_activation();
        return redirect()->back()->with("success",$result);
    }

    public function download_doc($id)
    {
        try {
            if (!Storage::disk("contracts_doc")->exists("/zip/{$id}/contract_{$id}_docs.zip")) {
                $zip = new ZipArchive();
                Storage::disk("contracts_doc")->makeDirectory("/zip/{$id}");
                if ($zip->open(public_path("/storage/contracts_doc/zip/{$id}/contract_{$id}_docs.zip"), ZipArchive::CREATE) === TRUE) {
                    $files = File::files(public_path("/storage/contracts_doc/{$id}"));
                    foreach ($files as $file)
                        $zip->addFile($file, basename($file));
                    $zip->close();
                }
            }
            $zip = public_path("/storage/contracts_doc/zip/{$id}/contract_{$id}_docs.zip");
            return Response::download($zip, "contract_{$id}_docs.zip");
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy_doc(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            Storage::disk("contracts_doc")->delete($request->input("filename"));
            if (Storage::disk("contracts_doc")->exists("zip/{$request->input("id")}"))
                Storage::disk("contracts_doc")->deleteDirectory("zip/{$request->input("id")}");
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
