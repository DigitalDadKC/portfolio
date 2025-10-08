<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialUnitSize extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
