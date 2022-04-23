<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuItemRequest;
use App\Models\MenuAction;
use App\Models\MenuHeader;
use App\Models\MenuItem;
use App\Models\MenuTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MenuItemsController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $menu_items = MenuItem::query()->with(["menu_header","parent"])->get();
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
            $menu_headers = MenuHeader::all();
            $menu_actions = MenuAction::all();
            $menu_items = MenuItem::all();
            return view("desktop_dashboard.create_new_menu_item", ["menu_actions" => $menu_actions,"menu_headers" => $menu_headers,"menu_items" => $menu_items]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(MenuItemRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            if ($validated["main"]){
                $menu_action_ids = $validated["menu_action_id"];
                unset($validated["menu_action_id"]);
                $main_route = MenuAction::query()->findOrFail($validated["main"]);
                $validated["main_route"] = $main_route->action;
                unset($validated["main"]);
                $menu_item = MenuItem::query()->create($validated);
                $menu_item->actions()->sync($menu_action_ids);
            }
            else
                $menu_item = MenuItem::query()->create($validated);
            if ($request->hasFile('icon')) {
                $menu_item->update(["icon" => $request->file('icon')->hashName()]);
                Storage::disk('menu_item_icons')->put($menu_item->id, $request->file('icon'));
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
        Gate::authorize("adminUser");
        try {
            $menu_item = MenuItem::query()->with(["menu_header","parent", "actions"])->findOrFail($id);
            $menu_headers = MenuHeader::all();
            $menu_actions = MenuAction::all();
            $menu_items = MenuItem::all();
            return view("desktop_dashboard.edit_menu_item", ["menu_item" => $menu_item, "menu_headers" => $menu_headers, "menu_actions" => $menu_actions,"menu_items" => $menu_items]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(MenuItemRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $menu_item = MenuItem::query()->findOrFail($id);
            $menu_item->actions()->detach();
            $validated = $request->validated();
            if ($validated["main"]){
                $menu_action_ids = $validated["menu_action_id"];
                unset($validated["menu_action_id"]);
                $main_route = MenuAction::query()->findOrFail($validated["main"]);
                $validated["main_route"] = $main_route->action;
                unset($validated["main"]);
                $menu_item->update($validated);
                $menu_item->actions()->sync($menu_action_ids);
            }
            else
                $menu_item->update($validated);
            if ($request->hasFile('icon')) {
                Storage::disk("menu_item_icons")->delete("{$menu_item->id}/$menu_item->icon");
                $menu_item->update(["icon" => $request->file('icon')->hashName()]);
                Storage::disk('menu_item_icons')->put($menu_item->id, $request->file('icon'));
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
            $menu_item = MenuItem::query()->findOrFail($id);
            $menu_item->actions()->detach();
            $menu_item->delete();
            if (Storage::disk("menu_item_icons")->exists("{$menu_item->id}"))
                Storage::disk("menu_item_icons")->deleteDirectory("{$menu_item->id}");
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
