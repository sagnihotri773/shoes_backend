<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'images' => $this->manipulateImagePaths($this->images),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function manipulateImagePaths($images)
    {
        // Your image path manipulation logic goes here
        return array_map(function ($image) {
            return url("storage/{$image}");
        }, $images);
    }
}

