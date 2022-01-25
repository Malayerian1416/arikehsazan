<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    protected $table = "bank_accounts";
    protected $fillable = ["user_id","name","branch","branch_code","account_number","card_number","sheba_number","is_active","phone"];

    public function checks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Check::class,"bank_account_id");
    }
    public function check_papers(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(CheckPaper::class,Check::class,"bank_account_id","check_id","id","id");
    }
    public function docs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Doc::class,"docable","docable_type","docable_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
}
