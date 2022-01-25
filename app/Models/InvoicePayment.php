<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    use HasFactory;
    protected $table = "invoice_payments";
    protected $fillable = ["invoice_id","bank_name","amount_payed","deposit_kind_string","deposit_kind_number","payment_receipt_number","receipt_scan"];

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class,"invoice_id");
    }
}
