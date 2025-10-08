<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'date_created' => $this->date_created,
            'due_date' => $this->due_date,
            'reference' => $this->reference,
            'terms_and_conditions' => $this->terms_and_conditions,
            'discount' => $this->discount,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'paid' => $this->paid,
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
            'invoice_items' => InvoiceItemResource::collection($this->whenLoaded('invoice_items')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
