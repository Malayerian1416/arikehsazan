<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = "locations";
    protected $fillable = ["project_id","name","geoJson","is_active","user_id","hash"];

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class,"project_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
    public function change_activation(){
        if($this->is_active == 0) {
            $this->update(["is_active" => 1]);
            return "activated";
        }
        else if ($this->is_active == 1) {
            $this->update(["is_active" => 0]);
            return "deactivated";
        }
        return false;
    }
}
