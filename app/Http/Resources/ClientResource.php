<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'company' => $this->company,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'state' => StateResource::make($this->whenLoaded('state')),
            'zip' => $this->zip,
            'url' => $this->url,
            'outreaches' => OutreachResource::collection($this->whenLoaded('outreaches')),
            'employees' => $this->employees,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
