<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'invoice_id',
        'quantity',
        'unit_price',
    ];

    public function invoice(): BelongsTo {
        return $this->belongsTo(Invoice::class);
    }

    public function material(): BelongsTo {
        return $this->belongsTo(Material::class);
    }

}
