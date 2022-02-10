<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UserController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $users = User::query()->with(["role", "user", "permitted_project"])->where("is_admin", 0)->get();
            return view("desktop_dashboard.user_index", ["users" => $users]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("adminUser");
        try {
            $roles = Role::all();
            $projects = Project::all();
            return view("desktop_dashboard.create_new_user", ["roles" => $roles, "projects" => $projects]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(UserRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["password"] = Hash::make($validated["password"]);
            $validated["user_id"] = auth()->id();
            $user = User::query()->create($validated);
            $user->permitted_project()->sync($validated["project_id"]);
            if ($request->hasFile('sign')){
                Storage::disk('signs')->put($user->id,$request->file('sign'));
                $user->update(["sign" => $request->file("sign")->hashName()]);
            }
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable$ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("adminUser");
        try {
            $roles = Role::all();
            $projects = Project::all();
            $user = User::query()->with(["role", "permitted_project"])->findOrFail($id);
            return view("desktop_dashboard.edit_user", ["user" => $user, "roles" => $roles, "projects" => $projects]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(UserRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            if ($validated["password"])
                $validated["password"] = Hash::make($validated["password"]);
            else
                unset($validated["password"]);
            $validated["user_id"] = auth()->id();
            $user = User::query()->findOrFail($id);
            $user->update($validated);
            $user->permitted_project()->sync($validated["project_id"]);
            if ($request->hasFile('sign')){
                if (Storage::disk("signs")->exists("$user->id/$user->sign"))
                    Storage::disk("signs")->delete("$user->id/$user->sign");
                Storage::disk('signs')->put($user->id,$request->file('sign'));
                $user->update(["sign" => $request->file("sign")->hashName()]);
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
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $user = User::query()->findOrFail($id);
            $user->delete();
            if (Storage::disk("signs")->exists("$user->id/$user->sign"))
                Storage::disk("signs")->deleteDirectory($user->id);
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function set_activation($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            $user = User::query()->findOrFail($id);
            $result = $user->activation();
            switch ($result) {
                case 0:{return redirect()->back()->with(["result" => "deactivated"]);}
                case 1:{return redirect()->back()->with(["result" => "activated"]);}
                default:return redirect()->back()->with(["result" => "updated"]);
            }
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
