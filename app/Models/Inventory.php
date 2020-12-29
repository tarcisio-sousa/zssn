<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    public function survivors()
    {
        return $this->belongsToMany('App\Models\Survivor');
    }
    
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
