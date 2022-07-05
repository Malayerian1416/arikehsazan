<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\AbilityCategory;
use App\Models\MenuAction;
use App\Models\MenuHeader;
use App\Models\MenuItem;
use App\Models\MenuTitle;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;
use function Symfony\Component\Translation\t;

class RoleController extends Controller
{

    public function index()
    {
        if (Auth::user()->is_staff)
            Gate::authorize("index","Roles");
        try {
            $menu_headers = MenuHeader::query()->with(["items.actions","items.children"])->get();
            $roles = Role::query()->with("user")->get();
            return view("{$this->agent}.role_index", ["roles" => $roles, "menu_headers" => $menu_headers]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        if (Auth::user()->is_staff)
            Gate::authorize("create","Roles");
        try {
            $menu_headers = MenuHeader::query()->with(["items.actions","items.children"])->get();
            return view("{$this->agent}.create_new_role", ["menu_headers" => $menu_headers]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(RoleRequest $request): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->is_staff)
            Gate::authorize("create","Roles");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $role = Role::query()->create([
                "name" => $validated["name"],
                "user_id" => auth()->id()
            ]);
            foreach ($validated["role_menu"] as $menu_string) {
                $exploded = explode("#",$menu_string);
                $role->menu_items()->attach([$exploded[0] => ["menu_action_id" => $exploded[1],"route" =>  $exploded[2]]]);
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
        if (Auth::user()->is_staff)
            Gate::authorize("edit","Roles");
        try {
            $menu_headers = MenuHeader::query()->with(["items.actions","items.children"])->get();
            $role = Role::query()->with("menu_items.actions")->findOrFail($id);
            return view("{$this->agent}.edit_role", ["role" => $role, "menu_headers" => $menu_headers]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(RoleRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->is_staff)
            Gate::authorize("edit","Roles");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $role = Role::query()->findOrFail($id);
            $role->update([
                "name" => $validated["name"],
                "user_id" => auth()->id()
            ]);
            $role->menu_items()->detach();
            foreach ($validated["role_menu"] as $menu_string) {
                $exploded = explode("#",$menu_string);
                $role->menu_items()->attach([$exploded[0] => ["menu_action_id" => $exploded[1],"route" =>  $exploded[2]]]);
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
            Gate::authorize("destroy","Roles");
        try {
            DB::beginTransaction();
            $role = Role::query()->findOrFail($id);
            if ($role->users()->get()->isNotEmpty()){
                $related_users = "";
                foreach ($role->users()->get() as $user)
                    $related_users .= "$user->id,";
                $related_users = substr($related_users,0,-1);
                $related_users = "( $related_users )";
                return redirect()->back()->with(["action_error" => "کاربر یا کاربران شماره $related_users دارای وابستگی به رکورد مورد نظر می باشد."]);
            }
            $role->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
