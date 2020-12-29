<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survivor extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class);
    }
    
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
