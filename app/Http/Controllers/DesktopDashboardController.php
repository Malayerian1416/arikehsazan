<?php

namespace App\Http\Controllers;

use App\Models\CompanyInformation;

class DesktopDashboardController extends Controller
{
    public function index(){
        $company_information = CompanyInformation::all()->first();
        return view("desktop_dashboard/d_dashboard",["company_information" => $company_information]);
    }
}
