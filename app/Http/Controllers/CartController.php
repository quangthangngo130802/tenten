<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cloud;
use App\Models\DetailCart;
use App\Models\Hosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {

            $itemId = $request->input('item_id');
            $type = $request->input('type');
            $quantity = $request->input('quantity', 1);

            $user = Auth::user();

            if ($type == 'hosting') {
                $product = Hosting::find($itemId);
            } elseif ($type == 'cloud') {
                $product = Cloud::find($itemId);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Loại sản phẩm không hợp lệ.'
                ]);
            }

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại.'
                ]);
            }

            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['user_id' => $user->id, 'total_price' => 0]
            );

            $cart->total_price += $product->price;
            $cart->save();

            $detailCart = DetailCart::where('cart_id', $cart->id)
                ->where('product_id', $itemId)
                ->where('type', $type)
                ->first();

            if ($detailCart) {
                $detailCart->quantity += $quantity;
                $detailCart->save();
            } else {

                DetailCart::create([
                    'cart_id' => $cart->id,
                    'product_id' => $itemId,
                    'quantity' => $quantity,
                    'type' => $type,
                    'price' => $product->price
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng.',
                'count' =>  count(optional(Auth::user()->cart)->details ?? [])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function listcart()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        return view('customer.cart.index', compact('cart'));
    }

    public function updateQuantity(Request $request)
    {

        $detail = DetailCart::find($request->id);
        $detail->quantity = $request->quantity;

        $detail->save();
        $cart = Cart::find($detail->cart_id);
        $cart->total_price = $cart->details->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        });
        $cart->save();

        return response()->json([
            'message' => 'Cập nhật số lượng thành công!',
            'price' => number_format($detail->price * $detail->quantity, 0, ',', '.'). ' đ',
            'total_price' => number_format($cart->total_price, 0, ',', '.'). ' đ',
        ]);
    }
    public function deleteItem(Request $request){
        $detail = DetailCart::find($request->id);
        $cart = Cart::find($detail->cart_id);
        $detail->delete();
        $cart->total_price = $cart->details ? $cart->details->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        }) : 0;
        $cart->save();

        return response()->json([
           'message' => 'Xóa sản phẩm thành công!',
            'total_price' => number_format($cart->total_price, 0, ',', '.'). ' đ',
            'count' => count(optional(Auth::user()->cart)->details ?? [])
        ]);
    }
}
