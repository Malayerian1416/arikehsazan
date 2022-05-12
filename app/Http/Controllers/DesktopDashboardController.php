<?php

namespace App\Http\Controllers;

use App\Models\CompanyInformation;
use App\Models\Contract;
use App\Models\Contractor;
use App\Models\InvoiceAutomation;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DesktopDashboardController extends Controller
{
    public function index(){
        $company_information = CompanyInformation::query()->with("ceo")->first();
        $projects = Project::get_permissions(["contracts.invoices.payments","worker_automations.payments"]);
        $contracts = Contract::get_permissions(["project","contractor","invoices.payments"]);
        $contractors=Contractor::get_permissions(["docs","contract.invoices" => function($query){$query->with("payments")->where("invoices.is_final","=",0);}]);
        return view("desktop_dashboard.idle",[
            "company_information" => $company_information,"projects" => $projects,"contracts" => $contracts, "contractors" => $contractors
        ]);
    }
}
