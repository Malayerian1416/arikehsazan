<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDeduction extends Model
{
    use HasFactory;
    protected $table = "invoice_deductions";
    protected $fillable = ["invoice_id","user_id","description","amount"];
}
