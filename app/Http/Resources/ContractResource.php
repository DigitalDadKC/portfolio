<?php

namespace App\Http\Resources;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PricebookEffectiveDateResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = null;
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->Name,
            'contract' => $this->Contract_No,
            'admin_fee' => $this->Admin_Fee,
            'discount' => $this->Discount,
            'freight_free' => $this->Freight_Free,
            'effective_dates' => MaterialEffectiveDateResource::collection($this->materialEffectiveDate)->sortByDesc('date')->values()
        ];
    }
}