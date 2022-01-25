<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuItemRequest;
use App\Models\MenuAction;
use App\Models\MenuItem;
use App\Models\MenuTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Throwable;

class MenuItemsController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $menu_items = MenuItem::query()->with("menu_title.menu_header")->get();
            return view("desktop_dashboard.menu_items_index", ["menu_items" => $menu_items]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("adminUser");
        try {
            $menu_titles = MenuTitle::query()->with("menu_header")->get();
            $menu_actions = MenuAction::all();
            return view("desktop_dashboard.create_new_menu_item", ["menu_titles" => $menu_titles, "menu_actions" => $menu_actions]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(MenuItemRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            $validated = $request->validated();
            $menu_action_ids = $validated["menu_action_id"];
            unset($validated["menu_action_id"]);
            $main_route = MenuAction::query()->findOrFail($validated["main"]);
            $validated["main_route"] = $main_route->action;
            unset($validated["main"]);
            $menu_item = MenuItem::query()->create($validated);
            $menu_item->actions()->sync($menu_action_ids);
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
            $menu_item = MenuItem::query()->with(["menu_title", "actions"])->findOrFail($id);
            $menu_titles = MenuTitle::query()->with("menu_header")->get();
            $menu_actions = MenuAction::all();
            return view("desktop_dashboard.edit_menu_item", ["menu_item" => $menu_item, "menu_titles" => $menu_titles, "menu_actions" => $menu_actions]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(MenuItemRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            $validated = $request->validated();
            $menu_action_ids = $validated["menu_action_id"];
            unset($validated["menu_action_id"]);
            $main_route = MenuAction::query()->findOrFail($validated["main"]);
            $validated["main_route"] = $main_route->action;
            unset($validated["main"]);
            $menu_item = MenuItem::query()->findOrFail($id);
            $menu_item->update($validated);
            $menu_item->actions()->detach();
            $menu_item->actions()->sync($menu_action_ids);
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
            $menu_item = MenuItem::query()->findOrFail($id);
            $menu_item->actions()->detach();
            $menu_item->delete();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
