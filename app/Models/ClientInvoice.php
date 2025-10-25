<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInvoice extends Model
{
    USE HasFactory;
    public function client() {
        return $this->belongsTo(Client::class);
    }
}
