<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\PhoneBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;
use Throwable;

class PhonebookController extends Controller
{
    public function __construct()
    {
        $agent = new Agent();
        if ($agent->isDesktop())
            $this->agent = "desktop_dashboard";
        else if($agent->isPhone() || $agent->isTablet())
            $this->agent = "phone_dashboard";
        else if ($agent->robot())
            return view("errors/cant_detect_device");
        else
            return view("errors/cant_detect_device");
        return false;
    }
    public function index()
    {
        $contractors = Contractor::all();
        foreach ($contractors as $contractor){
            $old = PhoneBook::query()->where("name",$contractor->name)->first();
            if ($old)
                $old->update(["Phone_number_1",$contractor->tel,"phone_number_2",$contractor->cellphone,"address" => $contractor->address]);
            else
                PhoneBook::query()->insert(["name" => $contractor->name,"phone_number_1" => $contractor->tel,"phone_number_2" => $contractor->cellphone,"address" => $contractor->address]);
        }
        $phonebooks = PhoneBook::all();
        return view("{$this->agent}.phonebook_index", ["phonebooks" => $phonebooks]);
    }

    public function create()
    {
        return view("{$this->agent}.create_new_contact");
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            "name" => "required",
            "phone_number_1" => "sometimes|nullable",
            "phone_number_2" => "sometimes|nullable",
            "phone_number_3" => "sometimes|nullable",
            "job_title" => "sometimes|nullable",
            "email" => "sometimes|nullable|email",
            "address" => "sometimes|nullable",
            "note" => "sometimes|nullable"
        ],[
            "name.required" => "درج نام مخاطب الزامی می باشد",
            "email.email" => "ایمیل وارد شده در فرمت صحیح نمی باشد."
        ]);
        try {
            DB::beginTransaction();
            PhoneBook::query()->create($validated);
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            "name" => "required",
            "phone_number_1" => "sometimes|nullable",
            "phone_number_2" => "sometimes|nullable",
            "phone_number_3" => "sometimes|nullable",
            "job_title" => "sometimes|nullable",
            "email" => "sometimes|nullable|email",
            "address" => "sometimes|nullable",
            "note" => "sometimes|nullable"
        ],[
            "name.required" => "درج نام مخاطب الزامی می باشد",
            "email.email" => "ایمیل وارد شده در فرمت صحیح نمی باشد."
        ]);
        try {
            DB::beginTransaction();
            $contact = PhoneBook::query()->findOrFail($id);
            $contact->update($validated);
            DB::commit();
            return redirect()->back()->with(["result" => "updated"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage(),"error_modal" => 1]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $contact = PhoneBook::query()->findOrFail($id);
            $contact->delete();
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage(),"error_modal" => 1]);
        }
    }
}
