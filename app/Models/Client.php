<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    public function outreaches(): HasMany {
        return $this->hasMany(Outreach::class);
    }
    public function client_invoices(): HasMany {
        return $this->hasMany(ClientInvoice::class);
    }
    public function state(): BelongsTo {
        return $this->belongsTo(State::class);
    }
    public function contract(): HasMany {
        return $this->hasMany(Contract::class);
    }
    public function employees(): HasMany {
        return $this->hasMany(Employee::class);
    }
}
