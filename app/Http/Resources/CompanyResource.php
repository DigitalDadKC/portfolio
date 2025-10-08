<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'logo' => asset( '/storage/' . $this->logo),
            'address' => $this->address,
            'city' => $this->city,
            'state' => StateResource::make($this->state),
            'zip' => $this->zip,
            'terms' => $this->terms
        ];
    }
}
