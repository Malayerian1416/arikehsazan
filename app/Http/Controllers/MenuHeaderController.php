<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuHeaderRequest;
use App\Models\Icon;
use App\Models\MenuHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MenuHeaderController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $menu_headers = MenuHeader::query()->with("icon")->get();
            return view("desktop_dashboard.menu_header_index", ["menu_headers" => $menu_headers]);
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
            return view("desktop_dashboard.create_new_menu_header",["icons" => $icons]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(MenuHeaderRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $menu_header = MenuHeader::query()->create($validated);
            if ($request->hasFile('icon')) {
                $menu_header->update(["mobile_icon" => $request->file('icon')->hashName()]);
                Storage::disk('menu_header_icons')->put($menu_header->id, $request->file('icon'));
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
            $icons = Icon::all();
            $menu_header = MenuHeader::query()->with("icon")->findOrFail($id);
            return view("desktop_dashboard.edit_menu_header", ["menu_header" => $menu_header, "icons" => $icons]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(MenuHeaderRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $menu_header = MenuHeader::query()->findOrFail($id);
            $menu_header->update($validated);
            if ($request->hasFile('icon')) {
                Storage::disk("menu_header_icons")->delete("{$menu_header->id}/$menu_header->mobile_icon");
                $menu_header->update(["mobile_icon" => $request->file('icon')->hashName()]);
                Storage::disk('menu_header_icons')->put($menu_header->id, $request->file('icon'));
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
            $menu_header = MenuHeader::query()->findOrFail($id);
            if ($menu_header->menu_titles()->get()->isNotEmpty()) {
                $related_menu_titles = "";
                foreach ($menu_header->menu_titles()->get() as $menu_title)
                    $related_menu_titles .= "$menu_title->id,";
                $related_titles = substr($related_menu_titles, 0, -1);
                $related_titles = "( $related_titles )";
                return redirect()->back()->with(["action_error" => "عنوان اصلی یا عناوین اصلی شماره $related_titles دارای وابستگی به رکورد مورد نظر می باشد."]);
            }
            $menu_header->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
