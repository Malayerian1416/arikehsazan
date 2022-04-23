<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Location;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class LocationController extends Controller
{
    public function index()
    {
        $projects = Project::get_permissions([]);
        $locations = Location::query()->with("project")->get();
        return view("{$this->agent}.locations_index",["projects" => $projects, "locations" => $locations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(LocationRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("create","Locations");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["user_id"] = Auth::id();
            Location::query()->create($validated);
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            $location = Location::query()->findOrFail($id);
            $location->update($validated);
            DB::commit();
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
