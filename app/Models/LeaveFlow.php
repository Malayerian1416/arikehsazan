<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LeaveFlow extends Model
{
    use HasFactory;
    protected $fillable = ["role_id","is_starter","is_finisher","priority","is_main","quantity","amount","payment_offer"];
    protected $table = "leave_flow";

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class,"role_id");
    }
    public static function automate(): array
    {
        $previous_role_id = self::query()->where("role_id","=",Auth::user()->role->id)->get()->isNotEmpty() ? Auth::user()->role->id : 0;
        $current_role_id = 0;$next_role_id = 0;
        $max_priority = self::query()->max("priority");
        $previous_role_priority = $previous_role_id != 0 ? self::query()->where("role_id","=",$previous_role_id)->first()->priority : 1;
        $is_finished = 0;
        switch ($previous_role_priority){
            case 1:{
                $previous_role_id == 0 ? $current_role_id = self::query()->where("priority", "=", $previous_role_priority)->first()->role_id :
                    $current_role_id = self::query()->where("priority","=",++$previous_role_priority)->first()->role_id;
                if (++$previous_role_priority <= $max_priority)
                    $next_role_id = self::query()->where("priority", "=", $previous_role_priority)->first()->role_id;
                break;
            }
            case ($previous_role_priority < $max_priority - 1):{
                $current_role_id = self::query()->where("priority","=",++$previous_role_priority)->first()->role_id;
                $next_role_id = self::query()->where("priority","=",++$previous_role_priority)->first()->role_id;
                break;
            }
            case ($previous_role_priority == $max_priority - 1):{
                $current_role_id = self::query()->where("priority","=",++$previous_role_priority)->first()->role_id;
                break;
            }
            default:{
                $is_finished = 1;
            }
        }

        return [
            "previous_role_id"=>$previous_role_id,
            "current_role_id"=>$current_role_id,
            "next_role_id"=>$next_role_id,
            "is_read"=> $is_finished,
            "is_finished" => $is_finished
        ];
    }
    public static function refer($id)
    {
        $leave_automation = LeaveAutomation::query()->findOrFail($id);
        $previous_role_id = 0;$current_role_id = 0;$next_role_id = 0;
        $min_priority = self::query()->min("priority");
        $current_role_priority = self::query()->where("role_id","=",Auth::user()->role_id)->first()->priority;
        switch ($current_role_priority){
            case 1:{
                break;
            }
            case ($current_role_priority > $min_priority + 1):{
                --$current_role_priority;
                $previous_role_id = self::query()->where("priority","=",--$current_role_priority)->first()->role_id;
                $current_role_id = $leave_automation->previous_role_id;
                $next_role_id = $leave_automation->current_role_id;
                break;
            }
            case ($current_role_priority == $min_priority + 1):{
                $current_role_id = $leave_automation->previous_role_id;
                $next_role_id = $leave_automation->current_role_id;
                break;
            }
        }
        $leave_automation->update([
            "previous_role_id"=>$previous_role_id,
            "current_role_id"=>$current_role_id,
            "next_role_id"=>$next_role_id,
            "is_read"=>0
        ]);

    }
    public static function MainRole(){
        $main_role = self::query()->where("is_main","=",1)->first();
        return $main_role->role_id ?: 0;
    }
}
