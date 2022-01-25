<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    use HasFactory;
    protected $table = "checks";
    protected $fillable = ["user_id","bank_account_id","serial","sayyadi","start","end","sample"];

    public function bank(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BankAccount::class,"bank_account_id");
    }
    public function check_papers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CheckPaper::class,"check_id");
    }
}
