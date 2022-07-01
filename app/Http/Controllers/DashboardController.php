<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\CompanyInformation;
use App\Models\Invoice;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;
use Throwable;

class DashboardController extends Controller
{
    public function index(Request $request){
        try {
            $agent = new Agent();
            $company_information = CompanyInformation::query()->with("ceo")->first();
            if ($agent->isDesktop()) {
                return view("desktop_dashboard.idle", [
                    "company_information" => $company_information
                ]);
            } else if (!$agent->isPhone() || !$agent->isTablet()) {
                if ($request->has("level") && $request->has("parent_id")) {
                    $id = $request->parent_id;
                    $menu_name = "";
                    $menu_items = "";
                    switch ($request->level) {
                        case 1:
                        {
                            $menu_items = MenuItem::query()->with(["actions", "menu_header", "children"])->whereHas("menu_header", function ($query) use ($id) {
                                $query->where("id", $id);
                            })->get();
                            if ($menu_items->isNotEmpty())
                                $menu_name = $menu_items[0]->menu_header->name;
                            break;
                        }
                        case 2:
                        {
                            $menu_items = MenuItem::query()->with(["actions", "menu_header", "children"])->whereHas("parent", function ($query) use ($id) {
                                $query->where("id", $id);
                            })->get();
                            if ($menu_items->isNotEmpty())
                                $menu_name = $menu_items[0]->parent->short_name;
                            break;
                        }
                    }
                    return view("phone_dashboard/p_dashboard", ["company_information" => $company_information, "menu_name" => $menu_name, "menu_items" => $menu_items, "level" => $request->level]);
                } else
                    return view("phone_dashboard/p_dashboard", ["company_information" => $company_information]);
            } else if ($agent->robot())
                return view("errors/cant_detect_device");
            else
                return view("errors/cant_detect_device");
        }
        catch (Throwable $ex){
            dd($ex);
        }
    }
}
