<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    use HasFactory;
    protected $table = "contractors";
    protected $fillable = ['name','type','birth_date','father_name','national_code','identify_number','tel','cellphone','bank_name','bank_card_number','bank_account_number','bank_sheba_number','address','user_id'];
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function contract(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contract::class,"contractor_id");
    }
    public function banks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContractorBank::class,"contractor_id");
    }
    public function docs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Doc::class,"docable","docable_type","docable_id");
    }
}
