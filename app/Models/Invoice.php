<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'total',
        'client_id',
        'subtotal',
        'date',
        'due_date',
        'reference',
        'discount',
        'number',
        'terms_and_conditions'
    ];

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function invoice_items() {
        return $this->hasMany(InvoiceItem::class);
    }
}
