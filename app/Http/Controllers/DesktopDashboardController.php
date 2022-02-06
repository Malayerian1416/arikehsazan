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
        $company_information = CompanyInformation::all()->first();
        $projects = Project::get_permissions(["contracts.invoices.payments","worker_automations.payments"]);
        $contracts = Contract::get_permissions();
        return view("desktop_dashboard/d_dashboard",["company_information" => $company_information,"projects" => $projects,"contracts" => $contracts]);
    }
}
