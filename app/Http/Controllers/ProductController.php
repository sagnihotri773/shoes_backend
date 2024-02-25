<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function getProductBySlug($slug)
    {
        try {
            $product = Product::with('category', 'images','variants')->where('slug', $slug)->first();
           
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new ProductResource($product),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'selling_price' => 'required|numeric',
            'discount' => 'numeric',
            'price' => 'required|numeric',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        try {
            $slug = Str::slug($request->input('name'));
        // Check if the slug already exists
        $existingSlug = Product::where('slug', $slug)->exists();
        if ($existingSlug) {
            // If the slug already exists, modify it to make it unique
            $slug = $this->makeUniqueSlug($slug);
        }
            $request->merge(['slug'=>$slug]);
            
            $product = Product::create($request->except('images','variants'));

            $imagePaths = [];

            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('product_images', 'public');
                $imagePaths[] = $imagePath;
            }

            foreach ($request->input('variants') as $variant) {
                $product->variants()->attach($variant['id'], ['quantity' => $variant['quantity']]);
            }

            ProductImage::create([
                'product_id' => $product->id,
                'images' => $imagePaths
            ]);

            return response()->json([
                'success' => true,
                'data' => new ProductResource($product),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function makeUniqueSlug($slug)
{
    // Append a number to the slug to make it unique
    $baseSlug = $slug;
    $counter = 1;

    while (Product::where('slug', $slug)->exists()) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}
}
