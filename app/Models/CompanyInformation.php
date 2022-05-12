<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInformation extends Model
{
    use HasFactory;
    protected $table = "company_information";
    protected $fillable = ['name','address','phone','ceo','logo','app_ver','ceo_user_id'];

    public function ceo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"ceo_user_id");
    }
}
