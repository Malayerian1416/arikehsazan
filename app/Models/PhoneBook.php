<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneBook extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $table = "phonebook";
    protected $fillable = ["name","job_title","phone_number_1","phone_number_2","phone_number_3","email","address","note"];
}
