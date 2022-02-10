<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuTitleRequest;
use App\Models\Icon;
use App\Models\MenuHeader;
use App\Models\MenuTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MenuTitleController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $menu_titles = MenuTitle::query()->with(["menu_header", "menu_items"])->get();
            return view("desktop_dashboard.menu_titles_index", ["menu_titles" => $menu_titles]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("adminUser");
        try {
            $icons = Icon::all();
            $menu_headers = MenuHeader::query()->with("icon")->get();
            return view("desktop_dashboard.create_new_menu_title", ["menu_headers" => $menu_headers,"icons" => $icons]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(MenuTitleRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            MenuTitle::query()->create($validated);
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
            $icons = Icon::all();
            $menu_headers = MenuHeader::query()->with("icon")->get();
            $menu_title = MenuTitle::query()->with("menu_header")->findOrFail($id);
            return view("desktop_dashboard.edit_menu_title", ["menu_headers" => $menu_headers, "menu_title" => $menu_title, "icons" => $icons]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(MenuTitleRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $menu_title = MenuTitle::query()->findOrFail($id);
            $menu_title->update($validated);
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
            $menu_title = MenuTitle::query()->findOrFail($id);
            if ($menu_title->menu_items()->get()->isNotEmpty()) {
                $related_menu_items = "";
                foreach ($menu_title->menu_titles()->get() as $menu_item)
                    $related_menu_items .= "$menu_item->id,";
                $related_items = substr($related_menu_items, 0, -1);
                $related_items = "( $related_items )";
                return redirect()->back()->with(["action_error" => "عنوان فرعی یا عناوین فرعی شماره $related_items دارای وابستگی به رکورد مورد نظر می باشد."]);
            }
            $menu_title->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
