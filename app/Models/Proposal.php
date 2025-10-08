<?php

namespace App\Models;

use App\Models\Job;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proposal extends Model
{
    use HasFactory;
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
    public function scopes(): HasMany {
        return $this->hasMany(Scope::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
