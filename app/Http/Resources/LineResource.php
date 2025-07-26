<?php

namespace App\Http\Resources;

use DateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $total_days = Carbon::createFromDate($this->start_date)->diffInDays(Carbon::createFromDate($this->end_date));
        return [
            'id' => $this->id,
            'order' => $this->order,
            'description' => $this->description,
            'unit_of_measurement' => UnitOfMeasurementResource::make($this->unit_of_measurement),
            'days' => $this->days,
            'price' => $this->price,
            'quantity' => $this->quantity
        ];
    }
}
