<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPaymentAutomation extends Model
{
    use HasFactory;
    protected $table = "worker_payments_automation";
    protected $fillable = ["project_id","contractor_id","user_id","previous_role_id","current_role_id","next_role_id","amount","description","is_read","is_finished"];

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class,"project_id");
    }
    public function contractor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contractor::class,"contractor_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
    public function signs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WorkerPaymentSign::class,"worker_payments_id");
    }
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WorkerAutomationPayments::class,"worker_payments_automation_id");
    }
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WorkerPaymentAutomationComment::class,"automation_id");
    }
}
