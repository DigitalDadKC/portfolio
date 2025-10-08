<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Line extends Model
{
    use HasFactory;
    public function scope(): BelongsTo
    {
        return $this->belongsTo(Scope::class);
    }
    public function unit_of_measurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class);
    }
}
