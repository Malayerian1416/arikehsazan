<?php

namespace App\Providers;

use App\Models\CompanyInformation;
use App\Models\InvoiceAutomation;
use App\Models\MenuHeader;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkerPaymentAutomation;
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
            $menu_headers = MenuHeader::query()->with(["items.actions","items.children"])->get();
            $role = Role::query()->with("menu_items.actions")->findOrFail(Auth::user()->role_id);
            $user = User::query()->with("role")->findOrFail(Auth::id());
            $invoice = InvoiceAutomation::query()
                ->where("current_role_id","=",Auth::user()->role->id)
                ->where("is_read","=",0)->where("is_finished","=",0)->count();
            $worker = WorkerPaymentAutomation::query()
                ->where("current_role_id","=",Auth::user()->role->id)
                ->where("is_read","=",0)->where("is_finished","=",0)->count();
            $view->with(["company_information" => $company_information,"user" => $user, "menu_headers" => $menu_headers, "role" => $role,"invoice_count" => "$invoice", "worker_count" => $worker]);
        });
        View::composer('phone_dashboard.p_dashboard', function ($view) {
            $company_information = CompanyInformation::all()->first();
            $menu_headers = MenuHeader::query()->with(["items.actions","items.children"])->get();
            $role = Role::query()->with("menu_items.actions")->findOrFail(Auth::user()->role_id);
            $user = User::query()->with("role")->findOrFail(Auth::id());
            $view->with(["company_information" => $company_information,"user" => $user, "menu_headers" => $menu_headers, "role" => $role]);
        });
    }
}
