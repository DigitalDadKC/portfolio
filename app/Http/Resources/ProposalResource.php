<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ScopeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
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
            'contingency' => $this->contingency,
            'scopes' => ScopeResource::collection($this->scopes),
            'job' => JobResource::make($this->whenLoaded('job')),
            'type' => $this->type,
            'exclusions' => $this->exclusions,
            'estimator' => UserResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
