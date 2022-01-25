<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorBank extends Model
{
    use HasFactory;
    protected $table = "contractor_banks";
    protected $fillable = ["contractor_id","name","card","account","sheba"];

    public function contractor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contractor::class,"contractor_id");
    }
}
