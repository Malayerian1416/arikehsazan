<?php

namespace App\Http\Controllers;

use App\Models\CompanyInformation;
use App\Models\InvoiceAutomation;
use Illuminate\Support\Facades\Auth;

class DesktopDashboardController extends Controller
{
    public function index(){
        $company_information = CompanyInformation::all()->first();
        return view("desktop_dashboard/d_dashboard",["company_information" => $company_information]);
    }
}
