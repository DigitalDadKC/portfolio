<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function materialEffectiveDate(): HasMany
    {
        return $this->hasMany(MaterialEffectiveDate::class)->orderBy('effective_date', 'desc');
    }
}
