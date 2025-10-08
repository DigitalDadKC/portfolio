<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'description' => $this->description,
            'image' => asset('/storage/' . $this->image),
            'skills' => SkillResource::collection($this->skills),
            'project_url' => $this->project_url,
            'project_order' => $this->project_order,
            'active' => $this->active,
        ];
    }
}
