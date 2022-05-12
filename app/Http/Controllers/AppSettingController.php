<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppSettingRequest;
use App\Models\CompanyInformation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AppSettingController extends Controller
{
    public function index()
    {
        $users = User::query()->where("is_staff","=",1)->get();
        $company_information = CompanyInformation::query()->with("ceo")->firstOrFail();
        return view("{$this->agent}.app_settings",["company_information" => $company_information,"users" => $users]);
    }

    public function update(AppSettingRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            if ($request->hasFile("logo"))
                $validated["logo"] = $request->file("logo")->hashName();
            $company_information = CompanyInformation::query()->findOrFail($id);
            $company_information->update($validated);
            DB::commit();
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }

    }

}
