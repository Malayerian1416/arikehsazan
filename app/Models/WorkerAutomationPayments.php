<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerAutomationPayments extends Model
{
    use HasFactory;
    protected $table = "worker_automation_payments";
    protected $fillable = ["worker_payments_automation_id","bank_name","amount_payed","deposit_kind_string","deposit_kind_number","payment_receipt_number","payment_receipt_scan"];

    public function worker_automation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WorkerPaymentAutomation::class,"worker_payments_automation_id");
    }
}
