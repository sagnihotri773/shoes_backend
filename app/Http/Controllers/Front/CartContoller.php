<?php


namespace App\Http\Controllers\Front;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartContoller extends Controller
{

    public function addToCart(Request $request, $productId)
    {
        $cart = $request->session()->get('cart', []);

        // Check if the product is already in the cart
        if (isset($cart[$productId])) {
            // Increment quantity if already in the cart
            $cart[$productId]['quantity'] += 1;
        } else {
            // Add the product to the cart with a quantity of 1
            $cart[$productId] = [
                'quantity' => 1,
            ];
        }

        $request->session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'cart' => $cart,
        ]);
    }

    public function getCart(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $cartItems = [];

        foreach ($cart as $productId => $cartItem) {
            $product = Product::find($productId);

            if ($product) {
                $cartItems[] = [
                    'product_id' => $productId,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $cartItem['quantity'],
                    'total_price' => $product->price * $cartItem['quantity'],
                    // Add any other product details you want to include
                ];
            }
        }

        return response()->json([
            'success' => true,
            'cartItems' => $cartItems,
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
}