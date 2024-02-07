<?php


namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    
    public function addToFavorites(Request $request, $productId)
    {
        $favorites = $request->session()->get('favorites', []);

        // Check if the product is already in favorites
        if (!in_array($productId, $favorites)) {
            // Add the product to favorites
            $favorites[] = $productId;
        }

        $request->session()->put('favorites', $favorites);

        return response()->json([
            'success' => true,
            'message' => 'Product added to favorites successfully.',
            'favorites' => $favorites,
        ]);
    }

    public function clearCart(Request $request)
    {
        $request->session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.',
        ]);

    }

    public function clearFavorites(Request $request)
    {
        $request->session()->forget('favorites');

        return response()->json([
            'success' => true,
            'message' => 'Favorites cleared successfully.',
        ]);
    }

    public function removeFromFavorites(Request $request, $productId)
    {
        $favorites = $request->session()->get('favorites', []);

        // Check if the product is in favorites
        if (in_array($productId, $favorites)) {
            // Remove the product from favorites
            $updatedFavorites = array_diff($favorites, [$productId]);

            $request->session()->put('favorites', $updatedFavorites);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from favorites successfully.',
                'favorites' => $updatedFavorites,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in favorites.',
        ]);
    }
}
