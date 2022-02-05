<?php

namespace App\Providers;

use App\Models\CompanyInformation;
use App\Models\MenuHeader;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("path.public",function (){
            return base_path()."/public_html";
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('desktop_dashboard.d_dashboard', function ($view) {
            $company_information = CompanyInformation::all()->first();
            $role = Role::query()->with("menu_items")->findOrFail(Auth::user()->role_id);
            $user_menu = MenuHeader::with(["icon","menu_titles" => function($query) use($role){$query->whereHas("menu_items",function($query)use($role){$query->whereIn("menu_items.id",$role->menu_items->pluck("id"));});},
                "menu_titles.menu_items" => function($query) use($role){$query->whereIn("menu_items.id",$role->menu_items->pluck("id"))->with("actions");
                }])->whereHas("menu_items", function($query) use ($role){$query->whereIn("menu_items.id",$role->menu_items->pluck("id"));})->get();
            $user = User::query()->with("role")->findOrFail(Auth::id());
            foreach ($user_menu as $header){
                foreach ($header->menu_titles as $title){
                    foreach ($title->menu_items as $item){
                        for ($i = 0 ; $i < count($item->actions) ; $i++)
                            $item->actions[$i]->action = $title->route . "." . $item->actions[$i]->action;
                    }
                }
            };
            $view->with(["company_information" => $company_information, "user_menu" => $user_menu, "user" => $user]);
        });
        View::composer('phone_dashboard.p_dashboard', function ($view) {
            $company_information = CompanyInformation::all()->first();
            $role = Role::query()->with("menu_items")->findOrFail(Auth::user()->role_id);
            $user_menu = MenuHeader::with(["icon","menu_titles" => function($query) use($role){$query->whereHas("menu_items",function($query)use($role){$query->whereIn("menu_items.id",$role->menu_items->pluck("id"));});},
                "menu_titles.menu_items" => function($query) use($role){$query->whereIn("menu_items.id",$role->menu_items->pluck("id"))->with("actions");
                }])->whereHas("menu_items", function($query) use ($role){$query->whereIn("menu_items.id",$role->menu_items->pluck("id"));})->get();
            $user = User::query()->with("role")->findOrFail(Auth::id());
            foreach ($user_menu as $header){
                foreach ($header->menu_titles as $title){
                    foreach ($title->menu_items as $item){
                        for ($i = 0 ; $i < count($item->actions) ; $i++)
                            $item->actions[$i]->action = $title->route . "." . $item->actions[$i]->action;
                    }
                }
            };
            $view->with(["company_information" => $company_information, "user_menu" => $user_menu, "user" => $user]);
        });
    }
}
