<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveAutomationController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveFlowController;
use App\Http\Controllers\MenuActionController;
use App\Http\Controllers\MenuHeaderController;
use App\Http\Controllers\MenuItemsController;
use App\Http\Controllers\PushController;
use \App\Http\Controllers\ReportsController;
use App\Http\Controllers\WorkShiftController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\BackUrlController;
use \App\Http\Controllers\ProjectController;
use \App\Http\Controllers\ContractController;
use \App\Http\Controllers\AxiosCallController;
use \App\Http\Controllers\ContractCategoryController;
use \App\Http\Controllers\UnitController;
use \App\Http\Controllers\ContractorController;
use \App\Http\Controllers\RoleController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\SystemStatusController;
use \App\Http\Controllers\MenuTitleController;
use \App\Http\Controllers\ContractBranchController;
use \App\Http\Controllers\InvoiceFlowController;
use \App\Http\Controllers\InvoiceController;
use \App\Http\Controllers\InvoiceAutomationController;
use \App\Http\Controllers\BankAccountController;
use \App\Http\Controllers\WorkerPaymentAutomationController;
use \App\Http\Controllers\InvoiceLimitedController;
use \App\Http\Controllers\WorkerController;
use \App\Http\Controllers\PhonebookController;
use \App\Http\Controllers\PrintController;
use \App\Http\Controllers\LocationController;
use \App\Http\Controllers\AttendanceController;
use \App\Http\Controllers\AppSettingController;
use \App\Helper\Helper;
use \App\Http\Controllers\DailyLeavesController;
use \App\Http\Controllers\HourlyLeavesController;
use \App\Http\Controllers\SalaryReportsController;

Auth::routes();
Route::get('/', function () {
    return redirect("login");
});
Route::group(['middleware' => ['auth']],function (){
    Route::post("/live_adding_data",[AxiosCallController::class,"live_data_adding"]);
    Route::post("/related_data_search",[AxiosCallController::class,"related_data_search"]);
    Route::post("/get_new_invoice_information",[AxiosCallController::class,"get_new_invoice_information"]);
    Route::post("/get_invoice_details",[AxiosCallController::class,"get_invoice_details"]);
    Route::post("/get_bank_account_information",[AxiosCallController::class,"get_bank_account_information"]);
    Route::post("/change_extra_deduction_content",[AxiosCallController::class,"change_extra_deduction_content"]);
    Route::get("/get_new_notification",[AxiosCallController::class,"get_new_notification"]);
    Route::post("/get_vapid_key",[AxiosCallController::class,"get_vapid_key"]);
    Route::get("/check_online",[AxiosCallController::class,"check_online"]);
    Route::post("/update_bank_information",[AxiosCallController::class,"update_bank_information"]);
    Route::post("/get_geo_json",[AxiosCallController::class,"get_geo_json"]);
    Route::post("/record_user_position",[AxiosCallController::class,"record_user_position"]);
    Route::post('/push',[PushController::class,"store"]);
    Route::get('/push',[PushController::class,"push"])->name('push');
});
Route::group(['prefix'=>'Dashboard/'. Helper::platform(), 'middleware'=>['auth','PreviousUrlSession']],function() {
    Route::get("/",[DashboardController::class,"index"])->name("idle");
    Route::post("/BackUrl",[BackUrlController::class,"get_back"])->name("back");
    Route::resource("/Projects",ProjectController::class)->except("show");
    Route::post("/Contracts/{id}",[ContractController::class,"contract_change_activation"])->name("contract_change_activation");
    Route::resource("/Contracts",ContractController::class)->except("show");
    Route::resource("/Contractors",ContractorController::class)->except("show");
    Route::resource("/Roles",RoleController::class)->except("show");
    Route::resource("/Users",UserController::class)->except("show");
    Route::put("/Users/activation/{id}",[UserController::class,"set_activation"])->name("Users.activation");
    Route::get("/ProjectDownloadDocs/{id}",[ProjectController::class,"download_doc"])->name("project_doc_download");
    Route::get("/ContractDownloadDocs/{id}",[ContractController::class,"download_doc"])->name("contract_doc_download");
    Route::get("/ContractorDownloadDocs/{id}",[ContractorController::class,"download_doc"])->name("contractor_doc_download");
    Route::get("/UserDownloadDocs/{id}",[UserController::class,"download_doc"])->name("user_doc_download");
    Route::get("/offline",function (){return view("auth.offline");});
    Route::delete("/DestroyProjectDoc",[ProjectController::class,"destroy_doc"])->name("DestroyProjectDoc");
    Route::delete("/DestroyContractDoc",[ContractController::class,"destroy_doc"])->name("DestroyContractDoc");
    Route::delete("/DestroyContractorDoc",[ContractorController::class,"destroy_doc"])->name("DestroyContractorDoc");
    Route::delete("/DestroyUserDoc",[UserController::class,"destroy_doc"])->name("DestroyUserDoc");
    Route::resource("/Invoices",InvoiceController::class)->except("show");
    Route::group(['prefix' => '/InvoiceAutomation'],function () {
        Route::get("/Automation", [InvoiceAutomationController::class, "get_automation_items"])->name("InvoiceAutomation.automation");
        Route::get("/Details/{id}", [InvoiceAutomationController::class, "view_details"])->name("InvoiceAutomation.details");
        Route::post("/NewAmounts/{id}", [InvoiceAutomationController::class, "register_invoice_amounts"])->name("InvoiceAutomation.amounts");
        Route::post("/Agree&Send/{id}", [InvoiceAutomationController::class, "automate_sending"])->name("InvoiceAutomation.automate_sending");
        Route::post("/Refer/{id}", [InvoiceAutomationController::class, "refer"])->name("InvoiceAutomation.refer");
        Route::post("/PaymentProcess/{id}", [InvoiceAutomationController::class, "payment_process"])->name("InvoiceAutomation.payment_process");
        Route::get("/Sent", [InvoiceAutomationController::class, "sent_invoices"])->name("InvoiceAutomation.sent");
        Route::get("/Details/Sent/{id}", [InvoiceAutomationController::class, "view_sent_details"])->name("InvoiceAutomation.sent.details");
        Route::get("/Print/{id}",[PrintController::class,"print_invoice"])->name("InvoiceAutomation.print");
    });
    Route::group(['prefix' => '/LeaveAutomation'],function () {
        Route::get("/Automation", [LeaveAutomationController::class, "get_automation_items"])->name("LeaveAutomation.automation");
        Route::get("/Details/{id}/{type}", [LeaveAutomationController::class, "view_details"])->name("LeaveAutomation.details");
        Route::post("/Agree&Send/{id}", [LeaveAutomationController::class, "automate_sending"])->name("LeaveAutomation.automate_sending");
        Route::post("/Refer/{id}", [LeaveAutomationController::class, "refer"])->name("LeaveAutomation.refer");
        Route::post("/Approved/{id}", [LeaveAutomationController::class, "approve"])->name("LeaveAutomation.approve");
        Route::post("/Rejected/{id}", [LeaveAutomationController::class, "reject"])->name("LeaveAutomation.reject");
        Route::get("/Details/Sent/{id}/{type}", [LeaveAutomationController::class, "view_sent_details"])->name("LeaveAutomation.sent.details");
        Route::get("/Print/{id}",[PrintController::class,"print_invoice"])->name("LeaveAutomation.print");
    });
    Route::resource("/DailyLeaves",DailyLeavesController::class);
    Route::resource("/HourlyLeaves",HourlyLeavesController::class);
    Route::delete("/DeleteDailyLeaveDoc",[DailyLeavesController::class,"delete_doc"])->name("DeleteDailyDoc.delete_doc");
    Route::delete("/DeleteHourlyLeaveDoc",[HourlyLeavesController::class,"delete_doc"])->name("DeleteHourlyDoc.delete_doc");
    Route::put("/HourlyLeaveAttendanceRegister/{id}",[HourlyLeavesController::class,"registration"])->name("hourly_leave_register_attendance");
    Route::resource("/Locations",LocationController::class);
    Route::post("/Locations/{id}",[LocationController::class,"location_change_activation"])->name("location_change_activation");
    Route::resource("/WorkShifts",WorkShiftController::class);
    Route::resource("/BankAccounts",BankAccountController::class)->except("show");
    Route::get("/CheckPrint",function (){return view("desktop_dashboard.check_print");});
    Route::group(['prefix' => '/WorkerPayments'],function (){
        Route::get("/create",[WorkerPaymentAutomationController::class,"create"])->name("WorkerPayments.create");
        Route::post("/store",[WorkerPaymentAutomationController::class,"store"])->name("WorkerPayments.store");
        Route::get("/Automation",[WorkerPaymentAutomationController::class,"get_automation_items"])->name("WorkerPayments.automation");
        Route::put("/Agree&Send/{id}",[WorkerPaymentAutomationController::class,"automate_sending"])->name("WorkerPayments.automate_sending");
        Route::get("/Payment/{id}",[WorkerPaymentAutomationController::class,"payment"])->name("WorkerPayments.payment");
        Route::put("/PaymentProcess/{id}",[WorkerPaymentAutomationController::class,"payment_process"])->name("WorkerPayments.payment_process");
        Route::post("/Refer/{id}", [WorkerPaymentAutomationController::class, "refer"])->name("WorkerPayments.refer");
        Route::get("/Sent",[WorkerPaymentAutomationController::class,"sent_worker_payments"])->name("WorkerPayments.sent");
        Route::get("/Index",[WorkerPaymentAutomationController::class,"index"])->name("WorkerPayments.index");
        Route::get("/Edit/{id}",[WorkerPaymentAutomationController::class,"edit"])->name("WorkerPayments.edit");
        Route::put("/Update/{id}",[WorkerPaymentAutomationController::class,"update"])->name("WorkerPayments.update");
        Route::delete("/Destroy/{id}",[WorkerPaymentAutomationController::class,"destroy"])->name("WorkerPayments.destroy");
        Route::get("/Print/{id}",[WorkerPaymentAutomationController::class,"print_payment"])->name("WorkerPayments.print");
    });
    Route::resource("/Phonebook",PhonebookController::class)->except(["show","edit"]);
    Route::resource("/InvoicesLimited",InvoiceLimitedController::class);
    Route::group(['prefix' => '/Workers'],function () {
        Route::get("/create", [WorkerController::class, "create"])->name("Workers.create");
        Route::post("/store", [WorkerController::class, "store"])->name("Workers.store");
    });
    Route::group(['prefix' => 'Reports'],function (){
        Route::get("/Project",[ReportsController::class,"project_reports_index"])->name("Reports.project_reports_index");
        Route::post("/Project",[ReportsController::class,"make_project_report"])->name("Reports.project_reports_make");
        Route::get("/ContractBranch",[ReportsController::class,"contract_branch_report_index"])->name("Reports.contract_branch_reports_index");
        Route::post("/ContractBranch",[ReportsController::class,"make_contract_branch_report"])->name("Reports.contract_branch_reports_make");
        Route::get("/ContractCategory",[ReportsController::class,"contract_category_report_index"])->name("Reports.contract_category_reports_index");
        Route::post("/ContractCategory",[ReportsController::class,"make_contract_category_report"])->name("Reports.contract_category_reports_make");
        Route::get("/Contract",[ReportsController::class,"contract_report_index"])->name("Reports.contract_reports_index");
        Route::post("/Contract",[ReportsController::class,"make_contract_report"])->name("Reports.contract_reports_make");
        Route::get("/Contractor",[ReportsController::class,"contractor_report_index"])->name("Reports.contractor_reports_index");
        Route::post("/Contractor",[ReportsController::class,"make_contractor_report"])->name("Reports.contractor_reports_make");
        Route::get("/Salary",[SalaryReportsController::class,"salary_report_index"])->name("Reports.salary_reports_index");
        Route::post("/Salary",[SalaryReportsController::class,"make_salary_report"])->name("Reports.salary_reports_make");
    });
    Route::get("/RegisterAttendance",[AttendanceController::class,"register_index"])->name("RegisterAttendance.index");
    Route::post("/RegisterAttendance",[AttendanceController::class,"register"])->name("RegisterAttendance.register");
    Route::resource("/Attendances",AttendanceController::class);
    Route::resource("/Leaves",LeaveController::class);
    Route::get("/Print/Salary",[PrintController::class,"print_salary_report"])->name("SalaryReport.print");
    Route::group(['prefix' => 'Admin','middleware' => ['AdminCheck']],function (){
        Route::get("/SystemStatus",[SystemStatusController::class,"index"])->name("system_status_index");
        Route::post("/ChangeSystemStatus",[SystemStatusController::class,"change_status"])->name("system_status_change");
        Route::resource("/MenuHeaders",MenuHeaderController::class)->except("show");
        Route::resource("/MenuTitles",MenuTitleController::class)->except("show");
        Route::resource("/MenuItems",MenuItemsController::class)->except("show");
        Route::resource("/MenuActions",MenuActionController::class)->except("show");
        Route::resource("/ContractBranches",ContractBranchController::class)->except("show","create","edit");
        Route::resource("/ContractCategories",ContractCategoryController::class)->except("show","create","edit");
        Route::resource("/Units",UnitController::class)->except("show","create","edit");
        Route::get("/InvoiceFlow/index",[InvoiceFlowController::class,"index"])->name("InvoiceFlow.index");
        Route::get("/InvoiceFlow/create",[InvoiceFlowController::class,"create"])->name("InvoiceFlow.create");
        Route::post("/InvoiceFlow/store",[InvoiceFlowController::class,"store"])->name("InvoiceFlow.store");
        Route::get("/InvoiceFlow/permissions",[InvoiceFlowController::class,"permissions"])->name("InvoiceFlow.permissions");
        Route::post("/InvoiceFlow/SetPermissions",[InvoiceFlowController::class,"set_permissions"])->name("InvoiceFlow.set_permissions");
        Route::get("/LeaveFlow/index",[LeaveFlowController::class,"index"])->name("LeaveFlow.index");
        Route::get("/LeaveFlow/create",[LeaveFlowController::class,"create"])->name("LeaveFlow.create");
        Route::post("/LeaveFlow/store",[LeaveFlowController::class,"store"])->name("LeaveFlow.store");
        Route::get("/LeaveFlow/permissions",[LeaveFlowController::class,"permissions"])->name("LeaveFlow.permissions");
        Route::post("/LeaveFlow/SetPermissions",[LeaveFlowController::class,"set_permissions"])->name("LeaveFlow.set_permissions");
        Route::resource("/AppSettings",AppSettingController::class)->except("show","destroy","create","store","edit");
        Route::get('/link-storage', function () {
            Artisan::call('storage:link');
        })->name("link_storage");
        Route::get('/serve-websocket', function(){
            Artisan::call('websockets:serve');
        })->name("serve_websocket");
        Route::get('/clear', function () {
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
        })->name("clear_cache");
        Route::get('/run-schedule', function(){
            Artisan::call('schedule:work');
        })->name("run_schedule");
    });
});
Route::get("/set",function (){
    $i = 01;
    for ($j = 1;$j < 8000;$j++){
        \Illuminate\Support\Facades\DB::table("attendances")->where("day","=","0$j")->update(["day" => $j]);
    }
});
