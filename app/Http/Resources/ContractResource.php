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
            'price' => $this->price,
            'date' => $this->date,
            'client' => ClientResource::make($this->client),
            'employee' => EmployeeResource::make($this->employee),
            'sent' => !!$this->sent,
            'status' => $this->status,
            'signwell_id' => $this->signwell_id,
            'service_ids' => ServiceResource::collection($this->services)->pluck('id')->toArray(),
            'services' => ServiceResource::collection($this->services),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
