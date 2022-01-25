<?php

namespace App\Http\Controllers;

use App\Models\CompanyInformation;
use Illuminate\Http\Request;

class PhoneDashboardController extends Controller
{
    public function index(){
        $company_information = CompanyInformation::all()->first();
        return view("phone_dashboard/p_dashboard",["company_information" => $company_information]);
    }
}
