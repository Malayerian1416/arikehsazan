<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractBranch;
use App\Models\ContractCategory;
use App\Models\Contractor;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Jenssegers\Agent\Agent;
use Throwable;


class ReportsController extends Controller
{
    public function project_reports_index(){
        Gate::authorize("project_reports_index","Reports");
        try {
            $projects = Project::get_permissions([]);
            return view("{$this->agent}.project_reports",["projects" => $projects]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function make_project_report(Request $request){
    Gate::authorize("project_reports_make","Reports");
        $validated = $request->validate([
           "project_id" => "required|numeric",
            "from_date" => ["sometimes","nullable","regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/"],
            "to_date" => ["sometimes","nullable","regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/"]
        ],[
            "project_id.required" => "انتخاب پروژه الزامی می باشد.",
            "project_id.numeric" => "کد پروژه انتخاب شده در فرمت صحیح نمی باشد.",
            "from_date.regex" => "تاریخ ابتدا در فرمت صحیح نمی باشد.",
            "to_date.regex" => "تاریخ انتها در فرمت صحیح نمی باشد.",
        ]);
        try {
            $from_date = null;
            $to_date = null;
            $project_id = $validated["project_id"];
            if ($validated["from_date"])
                $from_date = $this->gregorian_date_convert($validated["from_date"],"/");
            if ($validated["to_date"])
                $to_date = $this->gregorian_date_convert($validated["to_date"],"/");
            $results = Invoice::query()->with(["contract.category.branch","contract.contractor","automation_amounts","payments","extras","deductions"])->whereHas("contract.project",function ($query) use ($project_id){
                $query->where("projects.id","=",$project_id);
            })->orderBy("contract_id")->orderBy("created_at")->get();
            if ($from_date && $to_date)
                $results = $results->whereBetween("created_at", ["$from_date", "$to_date"]);
            else if ($from_date && $to_date == '')
                $results = $results->where("created_at",">=",$from_date);
            else if ($to_date && $from_date == '')
                $results = $results->where("created_at","<=",$to_date);
            $projects = Project::get_permissions([]);
            return view("{$this->agent}.project_reports",[
                "projects" => $projects, "results" => $results, "project_id" => $validated["project_id"], "from_date" => $validated["from_date"], "to_date" => $validated["to_date"]
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function contract_branch_report_index(){
        Gate::authorize("contract_branch_reports_index","Reports");
        try{
            $projects = Project::get_permissions([]);
            $contract_branches = ContractBranch::all();
            return view("{$this->agent}.contract_branch_reports",["projects" => $projects, "contract_branches" => $contract_branches]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function make_contract_branch_report(Request $request){
        Gate::authorize("contract_branch_reports_make","Reports");
        $validated = $request->validate([
            "project_id" => "required|numeric",
            "contract_branch_id" => "required|numeric",
            "from_date" => ["sometimes","nullable","regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/"],
            "to_date" => ["sometimes","nullable","regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/"]
        ],[
            "project_id.required" => "انتخاب پروژه الزامی می باشد.",
            "project_id.numeric" => "کد پروژه انتخاب شده در فرمت صحیح نمی باشد.",
            "contract_branch_id.required" => "انتخاب رشته پیمان الزامی می باشد.",
            "contract_branch_id.numeric" => "کد رشته پیمان انتخاب شده در فرمت صحیح نمی باشد.",
            "from_date.regex" => "تاریخ ابتدا در فرمت صحیح نمی باشد.",
            "to_date.regex" => "تاریخ انتها در فرمت صحیح نمی باشد.",
        ]);
        try{
            $from_date = null;
            $to_date = null;
            $project_id = $validated["project_id"];
            $contract_branch_id = $validated["contract_branch_id"];
            if ($validated["from_date"])
                $from_date = $this->gregorian_date_convert($validated["from_date"],"/");
            if ($validated["to_date"])
                $to_date = $this->gregorian_date_convert($validated["to_date"],"/");
            $results = Invoice::query()->with(["contract.category","contract.contractor","automation_amounts","payments","extras","deductions"])->whereHas("contract.project",function ($query) use ($project_id){
                $query->where("projects.id","=",$project_id);
            })->whereHas("contract.category.branch",function($query) use($contract_branch_id){
                $query->where("contract_branches.id","=",$contract_branch_id);
            })->orderBy("contract_id")->orderBy("created_at")->get();
            if ($from_date && $to_date)
                $results = $results->whereBetween("created_at", ["$from_date", "$to_date"]);
            else if ($from_date && $to_date == '')
                $results = $results->where("created_at",">=",$from_date);
            else if ($to_date && $from_date == '')
                $results = $results->where("created_at","<=",$to_date);
            $projects = Project::get_permissions([]);
            $contract_branches = ContractBranch::all();
            return view("{$this->agent}.contract_branch_reports",[
                "projects" => $projects,
                "contract_branches" => $contract_branches,
                "results" => $results,
                "project_id" => $project_id,
                "contract_branch_id" => $contract_branch_id,
                "from_date" => $validated["from_date"],
                "to_date" => $validated["to_date"]
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function contract_category_report_index(){
        Gate::authorize("contract_category_reports_index","Reports");
        try{
            $projects = Project::get_permissions([]);
            $contract_branches = ContractBranch::all();
            return view("{$this->agent}.contract_category_reports",["projects" => $projects, "contract_branches" => $contract_branches]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function make_contract_category_report(Request $request){
        Gate::authorize("contract_category_reports_make","Reports");
        $validated = $request->validate([
            "project_id" => "required|numeric",
            "contract_branch_id" => "required|numeric",
            "contract_category_id" => "required|numeric",
            "from_date" => ["sometimes","nullable","regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/"],
            "to_date" => ["sometimes","nullable","regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/"]
        ],[
            "project_id.required" => "انتخاب پروژه الزامی می باشد.",
            "project_id.numeric" => "کد پروژه انتخاب شده در فرمت صحیح نمی باشد.",
            "contract_branch_id.required" => "انتخاب رشته پیمان الزامی می باشد.",
            "contract_branch_id.numeric" => "کد رشته پیمان انتخاب شده در فرمت صحیح نمی باشد.",
            "contract_category_id.required" => "انتخاب سرفصل پیمان الزامی می باشد.",
            "contract_category_id.numeric" => "کد سرفصل پیمان انتخاب شده در فرمت صحیح نمی باشد.",
            "from_date.regex" => "تاریخ ابتدا در فرمت صحیح نمی باشد.",
            "to_date.regex" => "تاریخ انتها در فرمت صحیح نمی باشد.",
        ]);
        try{
            $from_date = null;
            $to_date = null;
            $project_id = $validated["project_id"];
            $contract_branch_id = $validated["contract_branch_id"];
            $contract_category_id = $validated["contract_category_id"];
            if ($validated["from_date"])
                $from_date = $this->gregorian_date_convert($validated["from_date"],"/");
            if ($validated["to_date"])
                $to_date = $this->gregorian_date_convert($validated["to_date"],"/");
            $results = Invoice::query()->with(["contract.contractor","automation_amounts","payments","extras","deductions"])->whereHas("contract.project",function ($query) use ($project_id){
                $query->where("projects.id","=",$project_id);
            })->whereHas("contract.category.branch",function($query) use($contract_branch_id){
                $query->where("contract_branches.id","=",$contract_branch_id);
            })->whereHas("contract.category",function($query) use($contract_category_id){
                $query->where("contract_category.id","=",$contract_category_id);
            })->orderBy("contract_id")->orderBy("created_at")->get();
            if ($from_date && $to_date)
                $results = $results->whereBetween("created_at", ["$from_date", "$to_date"]);
            else if ($from_date && $to_date == '')
                $results = $results->where("created_at",">=",$from_date);
            else if ($to_date && $from_date == '')
                $results = $results->where("created_at","<=",$to_date);
            $projects = Project::get_permissions([]);
            $contract_branches = ContractBranch::all();
            $contract_categories = ContractBranch::query()->findOrFail($contract_branch_id)->categories()->get(["id","category"])->flatten()->toArray();
            return view("{$this->agent}.contract_category_reports",[
                "projects" => $projects,
                "contract_branches" => $contract_branches,
                "contract_categories" => $contract_categories,
                "results" => $results,
                "project_id" => $project_id,
                "contract_branch_id" => $contract_branch_id,
                "contract_category_id" => $contract_category_id,
                "from_date" => $validated["from_date"],
                "to_date" => $validated["to_date"]
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function contract_report_index(){
        Gate::authorize("contract_reports_index","Reports");
        try{
            $projects = Project::get_permissions([]);
            $contract_branches = ContractBranch::all();
            return view("{$this->agent}.contract_reports",["projects" => $projects, "contract_branches" => $contract_branches]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function make_contract_report(Request $request){
        Gate::authorize("contract_reports_make","Reports");
        $validated = $request->validate([
            "project_id" => "required|numeric",
            "contract_branch_id" => "required|numeric",
            "contract_category_id" => "required|numeric",
            "contract_id" => "required|numeric",
            "from_date" => ["sometimes","nullable","regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/"],
            "to_date" => ["sometimes","nullable","regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/"]
        ],[
            "project_id.required" => "انتخاب پروژه الزامی می باشد.",
            "project_id.numeric" => "کد پروژه انتخاب شده در فرمت صحیح نمی باشد.",
            "contract_branch_id.required" => "انتخاب رشته پیمان الزامی می باشد.",
            "contract_branch_id.numeric" => "کد رشته پیمان انتخاب شده در فرمت صحیح نمی باشد.",
            "contract_category_id.required" => "انتخاب سرفصل پیمان الزامی می باشد.",
            "contract_category_id.numeric" => "کد سرفصل پیمان انتخاب شده در فرمت صحیح نمی باشد.",
            "contract_id.required" => "انتخاب عنوان پیمان الزامی می باشد.",
            "contract_id.numeric" => "کد عنوان پیمان انتخاب شده در فرمت صحیح نمی باشد.",
            "from_date.regex" => "تاریخ ابتدا در فرمت صحیح نمی باشد.",
            "to_date.regex" => "تاریخ انتها در فرمت صحیح نمی باشد.",
        ]);
        try{
            $from_date = null;
            $to_date = null;
            $project_id = $validated["project_id"];
            $contract_branch_id = $validated["contract_branch_id"];
            $contract_category_id = $validated["contract_category_id"];
            $contract_id = $validated["contract_id"];
            if ($validated["from_date"])
                $from_date = $this->gregorian_date_convert($validated["from_date"],"/");
            if ($validated["to_date"])
                $to_date = $this->gregorian_date_convert($validated["to_date"],"/");
            $results = Invoice::query()->with(["contract.contractor","automation_amounts","payments","extras","deductions"])->whereHas("contract.project",function ($query) use ($project_id){
                $query->where("projects.id","=",$project_id);
            })->whereHas("contract.category.branch",function($query) use($contract_branch_id){
                $query->where("contract_branches.id","=",$contract_branch_id);
            })->whereHas("contract.category",function($query) use($contract_category_id){
                $query->where("contract_category.id","=",$contract_category_id);
            })->whereHas("contract",function($query) use($contract_id){
                $query->where("contracts.id","=",$contract_id);
            })->orderBy("contract_id")->orderBy("created_at")->get();
            if ($from_date && $to_date)
                $results = $results->whereBetween("created_at", ["$from_date", "$to_date"]);
            else if ($from_date && $to_date == '')
                $results = $results->where("created_at",">=",$from_date);
            else if ($to_date && $from_date == '')
                $results = $results->where("created_at","<=",$to_date);
            $projects = Project::get_permissions([]);
            $contract_branches = ContractBranch::all();
            $contract_categories = ContractBranch::query()->findOrFail($contract_branch_id)->categories()->whereHas("contract",function ($query) use($project_id){$query->where("project_id","=",$project_id);})->get(["id","category"])->flatten()->toArray();
            $contracts = ContractCategory::query()->findOrFail($contract_category_id)->contract()->where("project_id","=",$project_id)->get(["id","name"])->flatten()->toArray();
            return view("{$this->agent}.contract_reports",[
                "projects" => $projects,
                "contract_branches" => $contract_branches,
                "contract_categories" => $contract_categories,
                "contracts" => $contracts,
                "results" => $results,
                "project_id" => $project_id,
                "contract_branch_id" => $contract_branch_id,
                "contract_category_id" => $contract_category_id,
                "contract_id" => $contract_id,
                "from_date" => $validated["from_date"],
                "to_date" => $validated["to_date"]
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function contractor_report_index(){
        Gate::authorize("contractor_reports_index","Reports");
        try{
            $contractors = Contractor::get_permissions([]);
            return view("{$this->agent}.contractor_reports",["contractors" => $contractors]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function make_contractor_report(Request $request){
        Gate::authorize("contractor_reports_make","Reports");
        $validated = $request->validate([
            "project_id" => "required|numeric",
            "contractor_id" => "required|numeric",
            "contract_id" => "required|numeric",
            "from_date" => ["sometimes","nullable","regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/"],
            "to_date" => ["sometimes","nullable","regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/"]
        ],[
            "project_id.required" => "انتخاب پروژه الزامی می باشد.",
            "project_id.numeric" => "کد پروژه انتخاب شده در فرمت صحیح نمی باشد.",
            "contractor_id.required" => "انتخاب پیمانکار الزامی می باشد.",
            "contractor_id.numeric" => "کد پیمانکار انتخاب شده در فرمت صحیح نمی باشد.",
            "contract_id.required" => "انتخاب عنوان پیمان الزامی می باشد.",
            "contract_id.numeric" => "کد عنوان پیمان انتخاب شده در فرمت صحیح نمی باشد.",
            "from_date.regex" => "تاریخ ابتدا در فرمت صحیح نمی باشد.",
            "to_date.regex" => "تاریخ انتها در فرمت صحیح نمی باشد.",
        ]);
        try{
            $from_date = null;
            $to_date = null;
            $project_id = $validated["project_id"];
            $contractor_id = $validated["contractor_id"];
            $contract_id = $validated["contract_id"];
            $results = '';
            if ($validated["from_date"])
                $from_date = $this->gregorian_date_convert($validated["from_date"],"/");
            if ($validated["to_date"])
                $to_date = $this->gregorian_date_convert($validated["to_date"],"/");
            if ($project_id == 0 && $contract_id == 0)
                $results = Invoice::query()->with(["contract.project","contract.category.branch","contract.contractor","automation_amounts","payments","extras","deductions"])
                    ->whereHas("contract.contractor",function ($query) use ($contractor_id){
                        $query->where("contractors.id","=",$contractor_id);
                    })->orderBy("contract_id")->orderBy("created_at")->get();
            if ($project_id != 0 && $contract_id == 0)
                $results = Invoice::query()->with(["contract.project","contract.category.branch","contract.contractor","automation_amounts","payments","extras","deductions"])
                    ->whereHas("contract.contractor",function ($query) use ($contractor_id){
                    $query->where("contractors.id","=",$contractor_id);
                })->whereHas("contract.project",function ($query) use($project_id){$query->where("projects.id",$project_id);})
                    ->orderBy("contract_id")->orderBy("created_at")->get();
            if ($project_id == 0 && $contract_id != 0)
                $results = Invoice::query()->with(["contract.project","contract.category.branch","contract.contractor","automation_amounts","payments","extras","deductions"])
                    ->whereHas("contract.contractor",function ($query) use ($contractor_id){
                        $query->where("contractors.id","=",$contractor_id);
                    })->whereHas("contract",function ($query) use($contract_id){$query->where("contracts.id",$contract_id);})
                    ->orderBy("contract_id")->orderBy("created_at")->get();
            if ($project_id != 0 && $contract_id != 0)
                $results = Invoice::query()->with(["contract.project","contract.category.branch","contract.contractor","automation_amounts","payments","extras","deductions"])
                    ->whereHas("contract.contractor",function ($query) use ($contractor_id){
                        $query->where("contractors.id","=",$contractor_id);
                    })->whereHas("contract",function ($query) use($contract_id){$query->where("contracts.id",$contract_id);})
                    ->whereHas("contract.project",function ($query) use($project_id){$query->where("projects.id",$project_id);})
                    ->orderBy("contract_id")->orderBy("created_at")->get();
            if ($results) {
                if ($from_date && $to_date)
                    $results = $results->whereBetween("created_at", ["$from_date", "$to_date"]);
                else if ($from_date && $to_date == '')
                    $results = $results->where("created_at", ">=", $from_date);
                else if ($to_date && $from_date == '')
                    $results = $results->where("created_at", "<=", $to_date);
            }
            $contractors = Contractor::get_permissions([]);
            $projects = Project::query()->whereHas("contracts.contractor",function ($query) use ($contractor_id){$query->where("contractors.id",$contractor_id);})
                ->get(["id","name"])->flatten()->toArray();
            if ($project_id == 0)
                $contracts = Contractor::query()->findOrFail($contractor_id)->contract()->get(["id","name"])->flatten()->toArray();
            else
                $contracts = Contractor::query()->findOrFail($contractor_id)->contract()->whereHas("project",function ($query) use($project_id){
                    $query->where("projects.id",$project_id);
                })->get(["id","name"])->flatten()->toArray();
            return view("{$this->agent}.contractor_reports",[
                "contractors" => $contractors,
                "projects" => $projects,
                "contracts" => $contracts,
                "results" => $results,
                "contractor_id" => $contractor_id,
                "project_id" => $project_id,
                "contract_id" => $contract_id,
                "from_date" => $validated["from_date"],
                "to_date" => $validated["to_date"]
            ]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
