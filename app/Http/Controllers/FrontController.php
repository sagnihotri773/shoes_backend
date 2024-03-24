<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function getCategories()
    {
        try {
            $categories = Category::select('id', 'name', 'status', 'slug', 'image')->get();
            return response()->json([
                'success' => true,
                'data' => CategoryResource::collection($categories),
                'meta'=>[
                    'table'=>[
                    "id",
                    "name",
                    "status",
                    "image",
                    "created_at",
                    "updated_at"
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching categories',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
