<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Location;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Throwable;

class LocationController extends Controller
{
    public function index()
    {
        try {
            $projects = Project::get_permissions([]);
            $locations = Location::query()->with("project")->get();
            return view("{$this->agent}.locations_index", ["projects" => $projects, "locations" => $locations]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(LocationRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","Locations");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["user_id"] = Auth::id();
            $validated["hash"] = Hash::make($validated["geoJson"].$validated["name"]);
            Location::query()->create($validated);
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("edit","Locations");
        try {
            $projects = Project::get_permissions([]);
            $location = Location::query()->with("project")->findOrFail($id);
            return view("{$this->agent}.edit_location",
                [
                    "location" => $location,
                    "projects" => $projects
                ]
            );
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(LocationRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","Locations");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["user_id"] = Auth::id();
            $validated["hash"] = Hash::make($validated["geoJson"].$validated["name"]);
            $location = Location::query()->findOrFail($id);
            $location->update($validated);
            DB::commit();
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id)
    {
        //
    }
    public function location_change_activation($id): \Illuminate\Http\RedirectResponse
    {
        $result = Location::query()->find($id)->change_activation();
        return redirect()->back()->with(["result" => $result]);
    }
}
