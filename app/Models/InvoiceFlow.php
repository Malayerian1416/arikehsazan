<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceFlow extends Model
{
    use HasFactory;
    protected $fillable = ["role_id","is_starter","is_finisher","priority","is_main","quantity","amount","payment_offer"];
    protected $table = "invoice_flow";

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class,"role_id");
    }
}
