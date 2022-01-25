<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckPaper extends Model
{
    use HasFactory;
    protected $table = "check_papers";
    protected $fillable = ["check_id","doc_id","check_number","amount","receipt_date","registered"];

    public function doc(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doc::class,"doc_id");
    }
    public function check(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Check::class,"check_id");
    }
}
