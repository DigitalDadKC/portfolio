<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
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
            'sku' => $this->sku,
            'name' => $this->name,
            '2023-01-01' => $this->{'2023-01-01'},
            '2023-01-01-status' => $this->{'2023-01-01-status'},
            '2020-01-01' => $this->{'2020-01-01'},
            '2020-01-01-status' => $this->{'2020-01-01-status'},
            '2017-01-01' => $this->{'2017-01-01'},
            '2017-01-01-status' => $this->{'2017-01-01-status'},
            '2014-01-01' => $this->{'2014-01-01'},
            '2014-01-01-status' => $this->{'2014-01-01-status'},
            'discountable' => ($this->discountable ? 'Yes' : 'No'),
            'material_category' => MaterialCategoryResource::make($this->whenLoaded('materialCategory')),
            'material_unit_size' => MaterialUnitSizeResource::make($this->whenLoaded('materialUnitSize')),
        ];
    }
}
