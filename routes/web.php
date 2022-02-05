<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuActionController;
use App\Http\Controllers\MenuHeaderController;
use App\Http\Controllers\MenuItemsController;
use App\Models\Contract;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\DesktopDashboardController;
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
use \App\Http\Controllers\PhoneDashboardController;
use \App\Http\Controllers\WorkerPaymentAutomationController;
use \App\Http\Controllers\InvoiceLimitedController;
use \App\Http\Controllers\WorkerController;

Auth::routes();
Route::get('/', function () {
    return redirect("login");
});
Route::group(['prefix'=>'Dashboard', 'middleware'=>['auth']],function() {
    Route::get("/",[DashboardController::class,"DeviceCheck"]);
    Route::group(['prefix'=>'Desktop'],function (){
        Route::get("/",[DesktopDashboardController::class,"index"])->name("idle");
        Route::resource("/Projects",ProjectController::class)->except("show");
        Route::post("/Contracts/{id}",[ContractController::class,"contract_change_activation"])->name("contract_change_activation");
        Route::resource("/Contracts",ContractController::class)->except("show");
        Route::post("/live_adding_data",[AxiosCallController::class,"live_data_adding"]);
        Route::post("/related_data_search",[AxiosCallController::class,"related_data_search"]);
        Route::post("/get_new_invoice_information",[AxiosCallController::class,"get_new_invoice_information"]);
        Route::post("/get_bank_account_information",[AxiosCallController::class,"get_bank_account_information"]);
        Route::post("/change_extra_deduction_content",[AxiosCallController::class,"change_extra_deduction_content"]);
        Route::get("/get_new_notification",[AxiosCallController::class,"get_new_notification"]);
        Route::resource("/Contractors",ContractorController::class)->except("show");
        Route::resource("/Roles",RoleController::class)->except("show");
        Route::resource("/Users",UserController::class)->except("show");
        Route::put("/Users/activation/{id}",[UserController::class,"set_activation"])->name("Users.activation");
        Route::get("/ProjectDownloadDocs/{id}",[ProjectController::class,"download_doc"])->name("project_doc_download");
        Route::get("/ContractDownloadDocs/{id}",[ContractController::class,"download_doc"])->name("contract_doc_download");
        Route::get("/ContractorDownloadDocs/{id}",[ContractorController::class,"download_doc"])->name("contractor_doc_download");
        Route::get("/offline",function (){return view("auth.offline");});
        Route::delete("/DestroyProjectDoc",[ProjectController::class,"destroy_doc"])->name("DestroyProjectDoc");
        Route::delete("/DestroyContractDoc",[ContractController::class,"destroy_doc"])->name("DestroyContractDoc");
        Route::delete("/DestroyContractorDoc",[ContractorController::class,"destroy_doc"])->name("DestroyContractorDoc");
        Route::resource("/Invoices",InvoiceController::class)->except("show");
        Route::get("/InvoiceAutomation/New",[InvoiceAutomationController::class,"get_new_items"])->name("InvoiceAutomation.new");
        Route::get("/InvoiceAutomation/Details/{id}",[InvoiceAutomationController::class,"view_details"])->name("InvoiceAutomation.details");
        Route::post("/InvoiceAutomation/NewAmounts/{id}",[InvoiceAutomationController::class,"register_invoice_amounts"])->name("InvoiceAutomation.amounts");
        Route::post("/InvoiceAutomation/Agree&Send/{id}",[InvoiceAutomationController::class,"automate_sending"])->name("InvoiceAutomation.automate_sending");
        Route::post("/InvoiceAutomation/PaymentProcess/{id}",[InvoiceAutomationController::class,"payment_process"])->name("InvoiceAutomation.payment_process");
        Route::get("/InvoiceAutomation/Sent",[InvoiceAutomationController::class,"sent_invoices"])->name("InvoiceAutomation.sent");
        Route::get("/InvoiceAutomation/Details/Sent/{id}",[InvoiceAutomationController::class,"view_sent_details"])->name("InvoiceAutomation.sent.details");
        Route::resource("/BankAccounts",BankAccountController::class)->except("show");
        Route::get("/CheckPrint",function (){return view("desktop_dashboard.check_print");});
        Route::group(['prefix' => '/WorkerPayments'],function (){
            Route::get("/create",[WorkerPaymentAutomationController::class,"create"])->name("WorkerPayments.create");
            Route::post("/store",[WorkerPaymentAutomationController::class,"store"])->name("WorkerPayments.store");
            Route::get("/new",[WorkerPaymentAutomationController::class,"get_new_items"])->name("WorkerPayments.new");
            Route::put("/Agree&Send/{id}",[WorkerPaymentAutomationController::class,"automate_sending"])->name("WorkerPayments.automate_sending");
            Route::get("/Payment/{id}",[WorkerPaymentAutomationController::class,"payment"])->name("WorkerPayments.payment");
            Route::put("/PaymentProcess/{id}",[WorkerPaymentAutomationController::class,"payment_process"])->name("WorkerPayments.payment_process");
            Route::get("/Sent",[WorkerPaymentAutomationController::class,"sent_worker_payments"])->name("WorkerPayments.sent");
            Route::get("/Index",[WorkerPaymentAutomationController::class,"index"])->name("WorkerPayments.index");
            Route::get("/Edit/{id}",[WorkerPaymentAutomationController::class,"edit"])->name("WorkerPayments.edit");
            Route::put("/Update/{id}",[WorkerPaymentAutomationController::class,"update"])->name("WorkerPayments.update");
            Route::delete("/Destroy/{id}",[WorkerPaymentAutomationController::class,"destroy"])->name("WorkerPayments.destroy");
        });
        Route::resource("/InvoicesLimited",InvoiceLimitedController::class);
        Route::get("/Worker/create",[WorkerController::class,"create"])->name("Workers.create");
        Route::post("/Worker/store",[WorkerController::class,"store"])->name("Workers.store");
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
        });
    });
    Route::group(['prefix'=>'Phone'],function (){
        Route::get("/",[PhoneDashboardController::class,"index"])->name("idle");
    });
});
Route::get("/f",function (){
   dd(\Illuminate\Support\Facades\Hash::make("Masoud@5012140"));
});
Route::get("/m",function (){
    $r = ["بانک آینده","بانک اقتصادنوین","بانک ایران زمین","بانک پارسیان","بانک پاسارگاد","بانک تجارت"
,"بانک تجارتی ایران و اروپا"
,"بانک توسعه تعاون"
,"بانک توسعه صادرات ایران"
,"بانک خاورمیانه"
,"بانک دی"
,"بانک رفاه کارگران"
,"بانک سامان"
,"بانک سپه"
,"بانک سرمایه"
,"بانک سینا"
,"بانک شهر"
,"بانک صادرات ایران"
,"بانک صنعت و معدن"
,"بانک قرض‌الحسنه رسالت"
,"بانک قرض‌الحسنه مهر ایران"
,"بانک گردشگری"
,"بانک مسکن"
,"بانک مشترک ایران - ونزوئلا"
,"بانک ملت"
,"بانک ملی ایران"
,"بانک کارآفرین"
,"بانک کشاورزی"
,"پست بانک ایران"
,"مؤسسه اعتباری غیربانکی كاسپین"
,"مؤسسه اعتباری غیربانکی توسعه"
,"مؤسسه اعتباری غیربانکی ملل"
,"موسسه اعتباری غیربانکی نور"];
    foreach ($r as $t){
        \App\Models\Bank::query()->create(["name" => $t]);
    }

});
