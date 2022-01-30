<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPaymentSign extends Model
{
    use HasFactory;
    protected $table = "worker_payments_automation_signs";
    protected $fillable = ["worker_payments_id","user_id","sign"];

    public function worker_payments_automation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WorkerPaymentAutomation::class,"worker_payments_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
}
