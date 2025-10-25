<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientInvoiceItem extends Model
{
    use HasFactory;
    public function client_invoice() {
        return $this->belongsTo(ClientInvoice::class);
    }
}
