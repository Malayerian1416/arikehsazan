<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceFlow extends Model
{
    use HasFactory;
    protected $fillable = ["role_id","is_starter","is_finisher","priority","is_main","quantity","amount","payment_offer"];
    protected $table = "invoice_flow";

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class,"role_id");
    }
    public static function automate(): array
    {
        $previous_role_id = Auth::user()->role->id;$current_role_id = 0;$next_role_id = 0;
        $flow_roles = self::query()->where("is_starter","=",0)->get();
        if ($flow_roles->contains("role_id",Auth::user()->role->id))
        {
            $next_role = $flow_roles->where("role_id","=",Auth::user()->role->id)->first();
            if ($next_role->is_finisher == 1)
                $current_role_id = $next_role->role_id;
            else{
                $priority = $next_role->priority+1;
                $next_role = $flow_roles->where("priority","=",$priority)->first();
                if ($next_role->is_finisher == 1)
                    $current_role_id = $next_role->role_id;
                else{
                    $after_next_priority = $priority + 1;
                    $after_next_role = $flow_roles->where("priority","=",$after_next_priority)->first();
                    $current_role_id = $next_role->role_id;
                    $next_role_id = $after_next_role->role_id;
                }
            }
        }
        else{
            $next_role = $flow_roles->where("priority","=",2)->first();
            if ($next_role->is_finisher == 1)
                $current_role_id = $next_role->role_id;
            else{
                $after_next_role = $flow_roles->where("priority","=",3)->first();
                $current_role_id = $next_role->role_id;
                $next_role_id = $after_next_role->role_id;
            }
        }
        return ["previous_role_id"=>$previous_role_id,"current_role_id"=>$current_role_id,"next_role_id"=>$next_role_id,"is_read"=>0];
    }
    public static function refer($id)
    {
        $invoice = Invoice::query()->findOrFail($id);
        $invoice_automation = InvoiceAutomation::query()->where("invoice_id","=",$id)->first();
        $flow_roles = self::query()->where("is_starter","=",0)->get();
        $previous_role_id = 0;$current_role_id = $invoice_automation->previous_role_id;$next_role_id = $invoice_automation->current_role_id;
        if ($flow_roles->contains("role_id",$current_role_id)){
            $previous_role = $flow_roles->where("role_id","=",$current_role_id)->first();
            $priority = $previous_role->priority - 1;
            if ($priority > 1){
                $previous_role = $flow_roles->where("priority","=",$priority)->first();
                $previous_role_id = $previous_role->role_id;
            }
            else
                $previous_role_id = User::query()->findOrFail($invoice->user_id)->role->id;
            $invoice_automation->update([
                "previous_role_id" => $previous_role_id,
                "current_role_id" => $current_role_id,
                "next_role_id" => $next_role_id,
                "is_read" => 0
            ]);
            $invoice->automation_amounts()->where("user_id", "=", Auth::user()->id)->delete();
            $invoice->signs()->where("user_id", "=", Auth::user()->id)->delete();
        }
        else {
            $previous_role_id = User::query()->findOrFail($invoice->user_id)->role->id;
            $invoice_automation->update([
                "previous_role_id" => $previous_role_id,
                "current_role_id" => 0,
                "next_role_id" => 0,
                "is_read" => 0
            ]);
        }
    }
    public static function worker_refer($id)
    {
        $worker_automation = Invoice::query()->findOrFail($id);
        $worker_automation = WorkerPaymentAutomation::query()->findOrFail($id);
        $flow_roles = self::query()->where("is_starter","=",0)->get();
        $previous_role_id = 0;$current_role_id = $worker_automation->previous_role_id;$next_role_id = $worker_automation->current_role_id;
        if ($flow_roles->contains("role_id",$current_role_id)){
            $previous_role = $flow_roles->where("role_id","=",$current_role_id)->first();
            $priority = $previous_role->priority - 1;
            if ($priority > 1){
                $previous_role = $flow_roles->where("priority","=",$priority)->first();
                $previous_role_id = $previous_role->role_id;
            }
            else
                $previous_role_id = User::query()->findOrFail($worker_automation->user_id)->role->id;
            $worker_automation->update([
                "previous_role_id" => $previous_role_id,
                "current_role_id" => $current_role_id,
                "next_role_id" => $next_role_id,
                "is_read" => 0
            ]);
            $worker_automation->signs()->where("user_id", "=", Auth::user()->id)->delete();
        }
        else {
            $previous_role_id = User::query()->findOrFail($worker_automation->user_id)->role->id;
            $worker_automation->update([
                "previous_role_id" => $previous_role_id,
                "current_role_id" => 0,
                "next_role_id" => 0,
                "is_read" => 0
            ]);
        }
    }
}
