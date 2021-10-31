<?php

namespace App\Http\Resources\Plant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PlantResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'species' => $this->species,
            'watering_instructions' => $this->watering_instructions,
            'photo' => $this->photo,
        ];
    }
}
