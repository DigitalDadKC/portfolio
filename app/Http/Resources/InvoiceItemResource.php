<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
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
            'invoice' => InvoiceResource::make($this->whenLoaded('invoice')),
            'material' => MaterialResource::make($this->whenLoaded('material')),
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price/100,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
