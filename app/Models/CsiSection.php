<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CsiSection extends Model
{
    public function csi_division(): BelongsTo
    {
        return $this->belongsTo(CsiDivision::class);
    }
    public function csi_subsection(): HasMany {
        return $this->hasMany(CsiSubsection::class);
    }
}
