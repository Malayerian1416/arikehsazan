<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Throwable;
use ZipArchive;

class UserController extends Controller
{

    public function index()
    {
        if (Auth::user()->is_staff)
            Gate::authorize("index","Users");
        try {
            $docs = [];
            $shifts = WorkShift::all();
            $users = User::query()->with(["role", "user", "permitted_project","work_shift"])->where("is_admin", 0)->get();
            $roles = Role::query()->where("name","<>","ادمین")->get();
            $projects = Project::all();
            foreach ($users as $user) {
                if (Storage::disk('users_doc')->exists("$user->id"))
                    $docs[] = $user->id;
            }
            return view("{$this->agent}.user_index", ["users" => $users, "roles" => $roles, "projects" => $projects, "docs" => $docs, "shifts" => $shifts]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(UserRequest $request): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->is_staff)
            Gate::authorize("create","Users");
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
            if ($request->hasFile('agreement_sample')){
                foreach ($request->file('agreement_sample') as $file)
                    Storage::disk('users_doc')->put($user->id,$file);
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
        if (Auth::user()->is_staff)
            Gate::authorize("edit","Users");
        try {
            $docs = null;
            $roles = Role::all();
            $shifts = WorkShift::all();
            $projects = Project::all();
            $user = User::query()->with(["role", "permitted_project","work_shift"])->findOrFail($id);
            if (Storage::disk('users_doc')->exists("$id"))
                $docs = Storage::disk('users_doc')->allFiles("$id");
            return view("{$this->agent}.edit_user", ["user" => $user, "roles" => $roles, "projects" => $projects, "docs" => $docs, "shifts" => $shifts]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(UserRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->is_staff)
            Gate::authorize("edit","Users");
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
            if ($request->hasFile('agreement_sample')){
                foreach ($request->file('agreement_sample') as $file)
                    Storage::disk('users_doc')->put($user->id,$file);
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
        if (Auth::user()->is_staff)
            Gate::authorize("destroy" , "Users");
        try {
            DB::beginTransaction();
            $user = User::query()->findOrFail($id);
            $user->delete();
            if (Storage::disk("signs")->exists("$user->id/$user->sign"))
                Storage::disk("signs")->deleteDirectory($user->id);
            if (Storage::disk("users_doc")->exists("$user->id"))
                Storage::disk("users_doc")->deleteDirectory($user->id);
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
        if (Auth::user()->is_staff)
            Gate::authorize("active","Users");
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
    public function download_doc($id)
    {
        try {
            if (!Storage::disk("users_doc")->exists("/zip/{$id}/user_{$id}_docs.zip")) {
                $zip = new ZipArchive();
                Storage::disk("users_doc")->makeDirectory("/zip/{$id}");
                if ($zip->open(public_path("/storage/users_doc/zip/{$id}/user_{$id}_docs.zip"), ZipArchive::CREATE) === TRUE) {
                    $files = File::files(public_path("/storage/users_doc/{$id}"));
                    foreach ($files as $file)
                        $zip->addFile($file, basename($file));
                    $zip->close();
                }
            }
            $zip = public_path("/storage/users_doc/zip/{$id}/user_{$id}_docs.zip");
            return Response::download($zip, "user_{$id}_docs.zip");
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
    public function destroy_doc(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            Storage::disk("users_doc")->delete($request->input("filename"));
            if (Storage::disk("users_doc")->exists("zip/{$request->input("id")}"))
                Storage::disk("users_doc")->deleteDirectory("zip/{$request->input("id")}");
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
