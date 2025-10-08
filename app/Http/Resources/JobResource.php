<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'address' => $this->address,
            'city' => $this->city,
            'state' => StateResource::make($this->state),
            'zip' => $this->zip,
            'start_date' => $this->start_date,
            'notes' => $this->notes,
            'proposals' => ProposalResource::collection(resource: $this->whenLoaded('proposals')),
            'customer' => CustomerResource::make($this->customer),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
