<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
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
    public function storeCategory(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'name' => 'required|string|max:255|unique:categories',
            // 'slug' => 'required|string|max:255|unique:categories',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
    

        try {
                $slug = Str::slug($request->input('name'));
                // Check if the slug already exists
                $existingSlug = Category::where('slug', $slug)->exists();
                if ($existingSlug) {
                    // If the slug already exists, modify it to make it unique
                    $slug = $this->makeUniqueSlug($slug);
                }

            $request->merge(['slug'=>$slug]);
            $category = new Category($request->only(['name', 'status', 'slug']));

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('category_images', 'public');
                $category->image = $imagePath;
            }

            $category->save();

            return response()->json([
                'success' => true,
                'data' => $category,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error storing category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            
        $categoryId=$request->input('category_id');
        $categoryData = Category::find($categoryId);
        $categoryData->status = $request->input('status');
      
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category_images', 'public');
            $categoryData->image = $imagePath;
        }

        $categoryData->save();

        return response()->json([
            'success' => true,
            'data' => $categoryData,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error storing category',
            'error' => $e->getMessage(),
        ], 500);
    }

    }


    public function getProductsByCategory($categoryName)
    {
        try {
            // Find the category by name
            $category = Category::with('products','products.images')->where('slug', $categoryName)->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            // Retrieve products associated with the category
            $products = $category->products;

            return response()->json([
                'success' => true,
                'data' =>ProductResource::collection($products),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching products by category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function productsWithFilters(Request $request)
    {
            try {
            $categorySlug=$request->input('category_slug');
            $searchKeyword = $request->input('searchkeyword');
            $orderBy = $request->input('orderby', 'price_low'); // Default order by price low
            $minPrice = $request->input('min_price', 0);
            $maxPrice = $request->input('max_price', PHP_INT_MAX);

            // Find the category
            $category = Category::where('slug', $categorySlug)->firstOrFail();

            // Your query logic based on the filters
            $query = $category->products();
            if ($searchKeyword) {
                $query->where('name', 'like', "%{$searchKeyword}%");
            }

            $query->whereBetween('selling_price', [$minPrice, $maxPrice]);

            // Add additional logic for other filters or sorting options

            // Order by
            if ($orderBy === 'price_high') {
                $query->orderBy('selling_price', 'desc');
            } else if($orderBy==='price_low') {
                $query->orderBy('selling_price', 'asc');
            } else if($orderBy==='recently_add') {
                $query->orderBy('created_at', 'desc');
            }

            // Get the results
            $products = $query->get();
            return response()->json([
                'success' => true,
                'data' =>ProductResource::collection($products),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching products by category',
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    public function getProducts(Request $request) {
        try {
            $inputs = $request->all();
            $perPage = $inputs['per_page'];
            $records = Product::with('images')->where('is_feature',1)->paginate($perPage);
            $maxPage = ceil($records->total() / $records->perPage());
            return response()->json([
                
                'data' =>ProductResource::collection($records),
                'pagination' => [
                    'current_page' => $records->currentPage(),
                    'per_page' => $records->perPage(),
                    'total' => $records->total(),
                    'next_page' => $records->currentPage()+1,
                    'max_page' =>$maxPage // Provide the next page URL
                ],
                'success' => true,
            ],200);
    
        } catch (\Throwable $th) {
            // Handle the exception if needed
        }
    }
    

    private function makeUniqueSlug($slug)
{
    // Append a number to the slug to make it unique
    $baseSlug = $slug;
    $counter = 1;

    while (Category::where('slug', $slug)->exists()) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}

    public function updateStatus(Request $request)
    {
        //status only 0 and 1
        $validator=Validator::make($request->all(),[
            'category_id' => 'required',
            'status'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $inputs = $request->all();
            Category::where('id', $inputs['category_id'])->update(['status'=> $inputs['status']]);
            // $categoryData=Category::find($inputs['category_id']);
            return response()->json([
                'success' => true,
                'data' => "Status Update",
            ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error storing category',
            'error' => $e->getMessage(),
        ], 500);
    }

    }

    //find

    public function edit(Request $request,$id){
        $category=Category::find($id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }
    
        return response()->json(['success' => true, 'data' => new CategoryResource($category)], 200);
    
    }


}


// category_slug: casual-shoes
// searchkeyword: text
// orderby: price_high
// min_price: 0
// max_price: 3999
// size_ids: 7,8,9
// variant_status: 0