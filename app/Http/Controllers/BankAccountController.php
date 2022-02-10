<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAccountRequest;
use App\Models\Bank;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Jenssegers\Agent\Agent;
use Throwable;

class BankAccountController extends Controller
{
    public function __construct()
    {
        $agent = new Agent();
        if ($agent->isDesktop())
            $this->agent = "desktop_dashboard";
        else if($agent->isPhone() || $agent->isTablet())
            $this->agent = "phone_dashboard";
        else if ($agent->robot())
            return view("errors/cant_detect_device");
        else
            return view("errors/cant_detect_device");
        return false;
    }
    public function index()
    {
        Gate::authorize("index","BankAccounts");
        try {
            $bank_accounts = BankAccount::query()->with(["checks","docs","user"])->get();
            return view("{$this->agent}.bank_accounts_index",["bank_accounts" => $bank_accounts]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("create","BankAccounts");
        try {
            $banks = Bank::all();
            return view("{$this->agent}.create_new_bank_account",["banks" => $banks]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(BankAccountRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","BankAccounts");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["user_id"] = Auth::id();
            $bank = BankAccount::query()->create($validated);
            $string = " واریز موجودی اولیه بابت افتتاح حساب";
            $bank->docs()->create([
                "user_id" => Auth::id(),
                "description" => $string,
                "amount" => $validated["balance"],
            ]);
            if ($request->has("check_serial")) {
                $counter = 0;
                foreach ($request->input("check_serial") as $check_serial) {
                    $tmp = [
                        "user_id" => Auth::id(),
                        "serial" => $check_serial,
                        "sayyadi" => $request->check_sayyadi[$counter],
                        "start" => $request->check_start[$counter],
                        "end" => $request->check_end[$counter],
                    ];
                    $bank->checks()->create($tmp);
                    ++$counter;
                }
            }
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("edit","BankAccounts");
        try {
            $banks = Bank::all();
            $bank_account = BankAccount::query()->with(["checks","docs"])->findOrFail($id);
            return view("{$this->agent}.edit_bank_account",["bank_account" => $bank_account,"banks" => $banks]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(BankAccountRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","BankAccounts");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["user_id"] = Auth::id();
            $bank = BankAccount::query()->findOrFail($id);
            $bank->update($validated);
            $bank->docs()->first()->update(["amount" => $validated["balance"]]);
            if ($request->has("check_serial")) {
                $counter = 0;
                foreach ($request->input("check_serial") as $check_serial) {
                    if ($check = $bank->checks->where("sayyadi","=",$request->check_sayyadi[$counter])->first()){
                        $check->update([
                            "user_id" => Auth::id(),
                            "serial" => $check_serial,
                            "sayyadi" => $request->check_sayyadi[$counter],
                            "start" => $request->check_start[$counter],
                            "end" => $request->check_end[$counter],
                        ]);
                        continue;
                    }
                    $tmp = [
                        "user_id" => Auth::id(),
                        "serial" => $check_serial,
                        "sayyadi" => $request->check_sayyadi[$counter],
                        "start" => $request->check_start[$counter],
                        "end" => $request->check_end[$counter],
                    ];
                    $bank->checks()->create($tmp);
                    ++$counter;
                }
            }
            DB::commit();
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("destroy","BankAccounts");
        try {
            DB::beginTransaction();
            $bank = BankAccount::query()->findOrFail($id);
            $bank->docs()->delete();
            $bank->checks()->delete();
            $bank->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
