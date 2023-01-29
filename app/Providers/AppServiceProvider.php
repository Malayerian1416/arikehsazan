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
use DateTime;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;
use function PHPUnit\Framework\throwException;
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
            try {
                $total_leave = ["days" => 0 , "hours" => 0 , "minutes" => 0];
                $daily_leaves = LeaveDay::query()->whereHas("daily_leave",function ($query){
                    $query->where("staff_id","=",Auth::id())->where("is_approved","=",1);
                })->count();
                $shift_duration = new DateTime(date("h:i",(strtotime(auth()->user()->work_shift->duration))));
                $shift_hour = intval($shift_duration->format("h"));
                $shift_minute = intval($shift_duration->format("i")) / 60;
                $shift_duration_numeric = $shift_hour + $shift_minute;
                $hourly_leaves = HourlyLeave::query()->where("staff_id","=",Auth::id())->where("is_approved","=",1)->get();
                $hourly_leaves_duration = 0;
                $check = [];
                foreach ($hourly_leaves as $hourly_leave){
                    $timestamp = $hourly_leave->timestamp;
                    if (!in_array($timestamp,$check)) {
                        $current = $hourly_leaves->where("timestamp", "=", $timestamp)->toArray();
                        foreach ($current as $day) {
                            $arrival = new DateTime(date("H:i",strtotime($day["arrival"])));
                            $departure = new DateTime(date("H:i",strtotime($day["departure"])));
                            $duration = $departure->diff($arrival);
                            $duration = round($duration->h + ($duration->i / 60),2);
                            if ($duration < $shift_duration_numeric / 2)
                                $hourly_leaves_duration += $duration;
                            else
                                $daily_leaves++;
                        }
                        $check[] = $timestamp;
                    }
                    else
                        continue;
                }
                $hourly_leaves_days = floor($hourly_leaves_duration / $shift_duration_numeric);
                $hourly_leaves_minute = round(($hourly_leaves_duration / $shift_duration_numeric) - $hourly_leaves_days,2) * $shift_duration_numeric;
                $daily_leaves += $hourly_leaves_days;
                $hours = floor($hourly_leaves_minute);
                $minutes = floor(($hourly_leaves_minute - $hours) * 60);
                $total_leave = ["days" => $daily_leaves , "hours" => $hours , "minutes" => $minutes];
            }
            catch (Throwable $ex){
                throwException($ex);
            }
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
                "leave_count" => $leave,
                "total_leave" => $total_leave
            ]);
        });
        View::composer('phone_dashboard.p_dashboard', function ($view) {
            try {
                $total_leave = ["days" => 0 , "hours" => 0 , "minutes" => 0];
                $daily_leaves = LeaveDay::query()->whereHas("daily_leave",function ($query){
                    $query->where("staff_id","=",Auth::id())->where("is_approved","=",1);
                })->count();
                $shift_duration = new DateTime(date("h:i",(strtotime(auth()->user()->work_shift->duration))));
                $shift_hour = intval($shift_duration->format("h"));
                $shift_minute = intval($shift_duration->format("i")) / 60;
                $shift_duration_numeric = $shift_hour + $shift_minute;
                $hourly_leaves = HourlyLeave::query()->where("staff_id","=",Auth::id())->where("is_approved","=",1)->get();
                $hourly_leaves_duration = 0;
                $check = [];
                foreach ($hourly_leaves as $hourly_leave){
                    $timestamp = $hourly_leave->timestamp;
                    if (!in_array($timestamp,$check)) {
                        $current = $hourly_leaves->where("timestamp", "=", $timestamp)->toArray();
                        foreach ($current as $day) {
                            $arrival = new DateTime(date("H:i",strtotime($day["arrival"])));
                            $departure = new DateTime(date("H:i",strtotime($day["departure"])));
                            $duration = $departure->diff($arrival);
                            $duration = round($duration->h + ($duration->i / 60),2);
                            if ($duration < $shift_duration_numeric / 2)
                                $hourly_leaves_duration += $duration;
                            else
                                $daily_leaves++;
                        }
                        $check[] = $timestamp;
                    }
                    else
                        continue;
                }
                $hourly_leaves_days = floor($hourly_leaves_duration / $shift_duration_numeric);
                $hourly_leaves_minute = round(($hourly_leaves_duration / $shift_duration_numeric) - $hourly_leaves_days,2) * $shift_duration_numeric;
                $daily_leaves += $hourly_leaves_days;
                $hours = floor($hourly_leaves_minute);
                $minutes = floor(($hourly_leaves_minute - $hours) * 60);
                $total_leave = ["days" => $daily_leaves , "hours" => $hours , "minutes" => $minutes];
            }
            catch (Throwable $ex){
                throwException($ex);
            }
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
                "leave_count" => $leave,
                "total_leave" => $total_leave
            ]);
        });
    }
}
