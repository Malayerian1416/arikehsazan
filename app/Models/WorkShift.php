<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;
    protected $table = "work_shifts";
    protected $fillable = ["name","arrival","departure","duration","user_id"];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }

    public function staffs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class,"work_shift_id");
    }
}
