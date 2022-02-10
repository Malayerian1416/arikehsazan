<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuActionRequest;
use App\Models\MenuAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class MenuActionController extends Controller
{

    public function index()
    {
        Gate::authorize("adminUser");
        try {
            $menu_actions = MenuAction::all();
            return view("desktop_dashboard.menu_actions_index", ["menu_actions" => $menu_actions]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize("adminUser");
        try {
            return view("desktop_dashboard.create_new_menu_action");
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(MenuActionRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            MenuAction::query()->create($validated);
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
            $menu_action = MenuAction::query()->findOrFail($id);
            return view("desktop_dashboard.edit_menu_action", ["menu_action" => $menu_action]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(MenuActionRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("adminUser");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $menu_action = MenuAction::query()->findOrFail($id);
            $menu_action->update($validated);
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
            $menu_action = MenuAction::query()->findOrFail($id);
            if ($menu_action->items()->get()->isNotEmpty()) {
                $related_menu_items = "";
                foreach ($menu_action->items()->get() as $menu_item)
                    $related_menu_items .= "$menu_item->id,";
                $related_items = substr($related_menu_items, 0, -1);
                $related_items = "( $related_items )";
                return redirect()->back()->with(["action_error" => "عنوان فرعی یا عناوین فرعی شماره $related_items دارای وابستگی به رکورد مورد نظر می باشد."]);
            }
            $menu_action->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}
