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
use Illuminate\Support\Facades\Gate;
use Throwable;
use function Symfony\Component\Translation\t;

class RoleController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $roles = Role::query()->with("user")->get();
            return view("desktop_dashboard.role_index", ["roles" => $roles]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("adminUser");
        try {
            $menu_headers = MenuHeader::query()->with("menu_titles.menu_items.actions")->get();
            return view("desktop_dashboard.create_new_role", ["menu_headers" => $menu_headers]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(RoleRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            $validated = $request->validated();
            $role = Role::query()->create([
                "name" => $validated["name"],
                "user_id" => auth()->id()
            ]);
            foreach ($validated["role_menu"] as $menu_string) {
                $exploded = explode("#",$menu_string);
                $role->menu_items()->attach([$exploded[0] => ["menu_action_id" => $exploded[1],"route" =>  $exploded[2]]]);
            }
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("adminUser");
        try {
            $menu_headers = MenuHeader::query()->with("menu_titles.menu_items.actions")->get();
            $role = Role::query()->with("menu_items.actions")->findOrFail($id);
            $role_menu = MenuItem::query()->whereIn("id", $role->menu_items->pluck("id"))->with("actions")->get();
            return view("desktop_dashboard.edit_role", ["role" => $role, "menu_headers" => $menu_headers, "role_menu" => $role_menu]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(RoleRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
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
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
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
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
