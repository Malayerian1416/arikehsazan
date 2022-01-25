<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractCategory extends Model
{
    use HasFactory;
    protected $fillable = ["category","user_id","contract_branch_id"];
    protected $table = "contract_category";
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ContractBranch::class,"contract_branch_id");
    }
    public function contract(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contract::class,"contract_category_id");
    }
}
