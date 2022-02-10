<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class UnitController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $units = Unit::query()->with("user")->get();
            return view("desktop_dashboard.unit_index", ["units" => $units]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            if ($request->input("name") != null) {
                Unit::query()->create([
                    "name" => $request->input("name"),
                    "user_id" => auth()->id()
                ]);
                DB::commit();
                return redirect()->back()->with(["result" => "saved"]);
            } else
                return redirect()->back()->with(["action_error" => "درج عنوان واحد شمارش الزامی می باشد."]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            if ($request->input("name") != null) {
                $unit = Unit::query()->findOrFail($id);
                $unit->update([
                    "name" => $request->input("name"),
                    "user_id" => auth()->id()
                ]);
                DB::commit();
                return redirect()->back()->with(["result" => "updated"]);
            }
            else
                return redirect()->back()->with(["action_error" => "درج عنوان واحد شمارش الزامی می باشد."]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $unit = Unit::query()->findOrFail($id);
            if ($unit->contract()->get()->isNotEmpty()) {
                $related_contracts = "";
                foreach ($unit->contract()->get() as $contract)
                    $related_contracts .= "$contract->id,";
                $related_contracts = substr($related_contracts,0,-1);
                $related_contracts = "( $related_contracts )";
                return redirect()->back()->with(["action_error" => "پیمان یا پیمان های شماره $related_contracts دارای وابستگی به رکورد مورد نظر می باشد."]);
            }
            $unit->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
