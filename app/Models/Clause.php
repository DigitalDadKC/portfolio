<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clause extends Model
{
    public function contract(): BelongsTo {
        return $this->belongsTo(Contract::class);
    }
}
