<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceAutomation extends Model
{
    use HasFactory;
    protected $table = "invoice_automation";
    protected $fillable = ["invoice_id","previous_role_id","current_role_id","next_role_id","is_read","is_finished"];

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class,"invoice_id");
    }

}
