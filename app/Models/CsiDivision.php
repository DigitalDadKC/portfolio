<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CsiDivision extends Model
{
    public function csi_section(): HasMany {
        return $this->hasMany(CsiSection::class);
    }
}
