<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkShiftRequest;
use App\Models\WorkShift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class WorkShiftController extends Controller
{

    public function index()
    {
        Gate::authorize("index","WorkShifts");
        try {
            $shifts = WorkShift::query()->with("user")->get();
            return view("{$this->agent}.work_shifts_index",["shifts" => $shifts]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }

    }


    public function store(WorkShiftRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","WorkShifts");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["duration"] = gmdate("H:i",strtotime($validated["departure"]) - strtotime($validated["arrival"]));
            $validated["user_id"] = Auth::id();
            WorkShift::query()->create($validated);
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
        Gate::authorize("edit","WorkShifts");
        try {
            $shift = WorkShift::query()->findOrFail($id);
            return view("{$this->agent}.edit_work_shift",["shift" => $shift]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(WorkShiftRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","WorkShifts");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["duration"] = gmdate("H:i",strtotime($validated["departure"]) - strtotime($validated["arrival"]));
            $validated["user_id"] = Auth::id();
            $shift = WorkShift::query()->findOrFail($id);
            $shift->update($validated);
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
        Gate::authorize("destroy","WorkShifts");
        try {
            DB::beginTransaction();
            $shift = WorkShift::query()->findOrFail($id);
            if ($shift->staffs()->get()->isNotEmpty()){
                $related_staffs = "";
                foreach ($shift->staffs()->get() as $user)
                    $related_staffs .= "$user->name,";
                $related_staffs = substr($related_staffs,0,-1);
                $related_users = "( $related_staffs )";
                return redirect()->back()->with(["action_error" => "کارمند یا کارمندان $related_users دارای وابستگی به رکورد مورد نظر می باشد."]);
            }
            $shift->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
