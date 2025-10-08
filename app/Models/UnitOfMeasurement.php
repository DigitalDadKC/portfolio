<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitOfMeasurement extends Model
{
    use HasFactory;
    public function lines(): HasMany
    {
        return $this->hasMany(Line::class);
    }
}
