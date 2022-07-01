<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPaymentAutomationComment extends Model
{
    use HasFactory;
    protected $fillable = ["automation_id","user_id","comment"];
    protected $table = "worker_payments_automation_comments";
    public function automation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WorkerPaymentAutomation::class,"automation_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
}
