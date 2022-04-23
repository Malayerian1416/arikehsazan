<?php

namespace App\Http\Controllers;

use App\Models\CompanyInformation;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class PhoneDashboardController extends Controller
{
    public function index(Request $request){
        $company_information = CompanyInformation::all()->first();
        if ($request->has("level") && $request->has("parent_id")){
            $id = $request->parent_id;
            switch ($request->level){
                case 1:{
                    $menu_items = MenuItem::query()->with(["actions","menu_header","children"])->whereHas("menu_header",function ($query) use ($id){
                        $query->where("id",$id);
                    })->get();
                    break;
                }
                case 2:{
                    $menu_items = MenuItem::query()->with(["actions","menu_header","children"])->whereHas("parent",function ($query) use ($id){
                        $query->where("id",$id);
                    })->get();
                    break;
                }
            }
            return view("phone_dashboard/p_dashboard",["company_information" => $company_information, "menu_items" => $menu_items, "level" => $request->level]);
        }
        else
            return view("phone_dashboard/p_dashboard",["company_information" => $company_information]);
    }
}
