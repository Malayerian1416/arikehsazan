<?php

namespace App\Providers;

use App\Models\CompanyInformation;
use App\Models\DailyLeave;
use App\Models\HourlyLeave;
use App\Models\InvoiceAutomation;
use App\Models\LeaveAutomation;
use App\Models\LeaveDay;
use App\Models\MenuHeader;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkerPaymentAutomation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use function RingCentral\Psr7\str;

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
        Paginator::useBootstrap();
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
            $leave = LeaveAutomation::query()
                ->where("current_role_id","=",Auth::user()->role->id)
                ->where("is_read","=",0)->where("is_finished","=",0)->count();
            $view->with([
                "company_information" => $company_information,
                "user" => $user,
                "menu_headers" => $menu_headers,
                "role" => $role,
                "invoice_count" => $invoice,
                "worker_count" => $worker,
                "leave_count" => $leave
            ]);
        });
        View::composer('phone_dashboard.p_dashboard', function ($view) {
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
            $leave = LeaveAutomation::query()
                ->where("current_role_id","=",Auth::user()->role->id)
                ->where("is_read","=",0)->where("is_finished","=",0)->count();
//            $daily_leaves = LeaveDay::query()->whereHas("daily_leave",function ($query){
//                $query->where("staff_id","=",Auth::id())->where("is_approved","=",1);
//            })->count();
//
//            $shift = date("H:i",(strtotime(auth()->user()->work_shift->duration) / 2));
//            $hourly_leaves = HourlyLeave::query()->where("staff_id","=",Auth::id())->where("is_approved","=",1)->get();
//            $hours = 0;
//            $check = [];
//            foreach ($hourly_leaves as $hourly_leave){
//                $duration = 0;
//                $timestamp = $hourly_leave->timestamp;
//                if (!in_array($timestamp,$check)) {
//                    $current = $hourly_leaves->where("timestamp", "=", $timestamp)->toArray();
//                    foreach ($current as $day)
//                        $duration += strtotime($day["arrival"]) - strtotime($day["departure"]);
//                    if ($duration >= $shift)
//                        $daily_leaves++;
//                    else
//                        $hours += strtotime($duration);
//                    $check[] = $timestamp;
//                }
//                else
//                    continue;
//            }
            $view->with([
                "company_information" => $company_information,
                "user" => $user,
                "menu_headers" => $menu_headers,
                "role" => $role,
                "invoice_count" => $invoice,
                "worker_count" => $worker,
                "leave_count" => $leave
            ]);
        });
    }
}
