<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialCategory extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
