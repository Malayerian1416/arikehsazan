<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;

class SystemStatusController extends Controller
{
    public function index(){
        Gate::authorize("adminUser");
        try {
            $status = 1;
            if (file_exists(storage_path() . '/framework/down'))
                $status = 0;
            return view("desktop_dashboard.system_status", ["status" => $status]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function change_status(): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            if (file_exists(storage_path() . '/framework/down'))
                Artisan::call('up');
            else
                Artisan::call('down');
            return redirect()->back()->with("changed");
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
