<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceAutomationAmounts extends Model
{
    use HasFactory;
    protected $table = "invoice_automation_amounts";
    protected $fillable = ["invoice_id","user_id","quantity","amount","payment_offer","payment_offer_percent","is_main"];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
    public static function main_amounts($invoice_id){
        return self::query()->where("is_main","=",1)->where("invoice_id","=",$invoice_id)->first();
    }
}
