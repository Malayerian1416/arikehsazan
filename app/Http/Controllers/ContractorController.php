<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractorRequest;
use App\Models\Bank;
use App\Models\Contractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Throwable;
use ZipArchive;

class ContractorController extends Controller
{
    public function index()
    {
        Gate::authorize("index","Contractors");
        try {
            $docs = [];
            $contractors = Contractor::all();
            $banks = Bank::all();
            foreach ($contractors as $contractor) {
                if (Storage::disk('contractors_doc')->exists("$contractor->id"))
                    $docs[] = $contractor->id;
            }
            return view("{$this->agent}.contractor_index", ["contractors" => $contractors, "docs" => $docs, "banks" => $banks]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("create","Contractors");
        try {
            $banks = Bank::all();
            return view("{$this->agent}.create_new_contractor", ["banks" => $banks]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(ContractorRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","Contractors");
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

    public function edit($id)
    {
        Gate::authorize("edit","Contractors");
        try {
            $docs = null;
            $banks = Bank::all();
            $contractor = Contractor::query()->with(["banks" => function ($query) {
                $query->select("contractor_id", "name", "card", "account", "sheba");
            }])->findOrFail($id);
            if (Storage::disk('contractors_doc')->exists("$id"))
                $docs = Storage::disk('contractors_doc')->allFiles("$id");
            return view("{$this->agent}.edit_contractor", ["contractor" => $contractor, "docs" => $docs, "banks" => $banks]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(ContractorRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","Contractors");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["user_id"] = auth()->id();
            $contractor = Contractor::query()->findOrFail($id);
            $contractor->update($validated);
            $contractor->banks()->delete();
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
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("destroy","Contractors");
        try {
            DB::beginTransaction();
            $contractor = Contractor::query()->findOrFail($id);
            if ($contractor->contract()->get()->isNotEmpty()){
                $related_contracts = "";
                foreach ($contractor->contract()->get() as $contract)
                    $related_contracts .= "$contract->id,";
                $related_contracts = substr($related_contracts,0,-1);
                $related_contracts = "( $related_contracts )";
                return redirect()->back()->with(["action_error" => "پیمان یا پیمان های شماره $related_contracts دارای وابستگی به رکورد مورد نظر می باشد."]);
            }
            $contractor->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function download_doc($id)
    {
        try {
            if (!Storage::disk("contractors_doc")->exists("/zip/{$id}/contractor_{$id}_docs.zip")) {
                $zip = new ZipArchive();
                Storage::disk("contractors_doc")->makeDirectory("/zip/{$id}");
                if ($zip->open(public_path("/storage/contractors_doc/zip/{$id}/contractor_{$id}_docs.zip"), ZipArchive::CREATE) === TRUE) {
                    $files = File::files(public_path("/storage/contractors_doc/{$id}"));
                    foreach ($files as $file)
                        $zip->addFile($file, basename($file));
                    $zip->close();
                }
            }
            $zip = public_path("/storage/contractors_doc/zip/{$id}/contractor_{$id}_docs.zip");
            return Response::download($zip, "contractor_{$id}_docs.zip");
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy_doc(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            Storage::disk("contractors_doc")->delete($request->input("filename"));
            if (Storage::disk("contractors_doc")->exists("zip/{$request->input("id")}"))
                Storage::disk("contractors_doc")->deleteDirectory("zip/{$request->input("id")}");
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
