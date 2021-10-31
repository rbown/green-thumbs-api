<?php

namespace App\Http\Resources\Plant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PlantCollection extends ResourceCollection
{
    public function toArray($request): Collection
    {
        return $this->collection->map(function ($plant) {
            return [
                'id' => $plant->id,
                'name' => $plant->name,
                'species' => $plant->species,
                'watering_instructions' => $plant->watering_instructions,
                'photo' => $this->parsePhoto($plant->photo),
                'created_at' => $plant->created_at
            ];
        });
    }

    private function parsePhoto($photo): string
    {
        if (Str::contains($photo, 'uploads')){
            return asset(sprintf('storage/%s', $photo));
            //return sprintf('%s/%s', env('APP_URL'), $photo);
        }

        if (!$photo){
            return 'https://via.placeholder.com/400x400';
        }

        return $photo;
    }
}
