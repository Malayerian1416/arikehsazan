<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceExtra extends Model
{
    use HasFactory;
    protected $table = "invoice_extras";
    protected $fillable = ["invoice_id","user_id","description","amount"];
}
