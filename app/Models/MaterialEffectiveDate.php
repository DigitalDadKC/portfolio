<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialEffectiveDate extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function contract()
    {
        return $this->belongsTo(Contract::class)->orderBy('effective_date', 'desc');
    }
}
