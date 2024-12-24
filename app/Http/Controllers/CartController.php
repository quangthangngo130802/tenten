<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cloud;
use App\Models\DetailCart;
use App\Models\Hosting;
use App\Models\Order;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Nette\Utils\Random;

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

        $details = DetailCart::find($request->id);
        $details->quantity = $request->quantity;

        $details->save();
        $cart = Cart::find($details->cart_id);
        $cart->total_price = $cart->details->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        });
        $cart->save();

        return response()->json([
            'message' => 'Cập nhật số lượng thành công!',
            'price' => number_format($details->price * $details->quantity, 0, ',', '.') . ' đ',
            'total_price' => number_format($cart->total_price, 0, ',', '.') . ' đ',
        ]);
    }
    public function deleteItem(Request $request)
    {
        $detail = DetailCart::find($request->id);
        $cart = Cart::find($detail->cart_id);
        $detail->delete();
        $cart->total_price = $cart->details ? $cart->details->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        }) : 0;
        $cart->save();

        return response()->json([
            'message' => 'Xóa sản phẩm thành công!',
            'total_price' => number_format($cart->total_price, 0, ',', '.') . ' đ',
            'count' => count(optional(Auth::user()->cart)->details ?? [])
        ]);
    }

    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        // Kiểm tra nếu số dư nhỏ hơn tổng giá trị giỏ hàng
        if ($user->wallet < $cart->total_price) {
            return response()->json([
                'message' => 'Tài khoản không đủ để thanh toán!',
            ], 400);
        }

        // Tạo đơn hàng
        $order = Order::create([
            'code' => Str::random(10),
            'email' => $user->email,
            'fullname' => $user->full_name,
            'amount' => $cart->total_price,
            'status' => 'payment',
            'payment' => 0,
        ]);

        // Duyệt qua chi tiết giỏ hàng và tạo các chi tiết đơn hàng
        $cart->details->each(function ($detail) use ($order) {
            if ($detail->type == 'cloud') {
                $product = Cloud::find($detail->product_id);
            } elseif ($detail->type == 'hosting') {
                $product = Hosting::find($detail->product_id);
            }

            $order->orderDetail()->create([
                'order_id'     => $order->id,
                'service_name' => $product->package_name,
                'quantity'     => $detail->quantity,
                'type'         => $detail->type,
                'amount'       => $product->price * $detail->quantity,
                'active'       => 'payment'
            ]);
            $detail->delete();
        });

        $cart->delete();

        $user->update([
            'wallet' => $user->wallet - $cart->total_price,
        ]);

        TransactionHistory::create([
            'code' => Str::random(10),
            'user_id' => $user->id,
            'type' => 'Thanh toán',
            'amount' => $cart->total_price,
            'type' => 2,
            'description' => 'Thanh toán đơn hàng #' . $order->code,
        ]);

        return response()->json([
            'message' => 'Thanh toán thành công!',
        ]);
    }
}
