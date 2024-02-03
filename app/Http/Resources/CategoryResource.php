<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;


class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'slug' => $this->slug,
            'image' => $this->manipulateImagePath($this->image), // Manipulate image path
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function manipulateImagePath($path)
    {
        // Your image path manipulation logic goes here
        return url("storage/{$path}"); // Example: prepend 'storage/' to the path
    }

}

class CategoryResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => CategoryResource::collection($this->collection),
        ];
    }
}
