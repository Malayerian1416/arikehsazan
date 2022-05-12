<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractorRequest;
use App\Http\Requests\WorkerRequest;
use App\Models\Bank;
use App\Models\Contractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Throwable;

class WorkerController extends Controller
{
    public function create()
    {
        Gate::authorize("create","Workers");
        try {
            $banks = Bank::all();
            return view("{$this->agent}.create_new_contractor_worker", ["banks" => $banks]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function store(WorkerRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","Workers");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["user_id"] = auth()->id();
            $contractor = Contractor::query()->create($validated);
            if ($request->hasFile('agreement_sample')){
                foreach ($request->file('agreement_sample') as $file)
                    Storage::disk('contractors_doc')->put($contractor->id,$file);
            }
            if ($request->has("bank_names")){
                $index = 0;
                foreach ($request->input("bank_names") as $bank){
                    $tmp = [
                        "name" => $bank,
                        "card" => $request->input("bank_cards")[$index],
                        "account"=>$request->input("bank_accounts")[$index],
                        "sheba"=>$request->input("bank_sheba")[$index]
                    ];
                    $contractor->banks()->create($tmp);
                    ++$index;
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
}
