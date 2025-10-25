<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientInvoice extends Model
{
    use HasFactory;
    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }
    public function client_invoice_items(): HasMany {
        return $this->hasMany(ClientInvoiceItem::class);
    }
}
