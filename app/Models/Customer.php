<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
