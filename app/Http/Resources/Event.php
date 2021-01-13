<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'event_type' => $this->event_type,
          'name' => $this->name,
          'address' => $this->address,
          'event_date' => $this->event_date,
          'start_time' => $this->start_time,
          'end_time' => $this->end_time,
          'min_head' => $this->min_head,
          'max_head' => $this->max_head,
          'memo' => $this->memo,
          'created_by' => $this->created_by,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at
        ];
    }
}
