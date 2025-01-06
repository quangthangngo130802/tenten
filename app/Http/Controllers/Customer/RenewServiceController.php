<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Cloud;
use App\Models\Hosting;
use App\Models\OrderDetail;
use App\Models\RenewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RenewServiceController extends Controller
{
    //
    public function addrenews($id)
    {
        // dd($id);
        $detail = OrderDetail::find($id);
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        if ($cart) {
            $cart->details()->delete();
            $cart->delete();
        }
        if($detail->type == 'cloud'){
            $price = Cloud::find($detail->product_id)->price;
            $number = 1;
        }else if($detail->type == 'hosting'){
            $price = Hosting::find($detail->product_id)->price;
            $number = 12;
        }
        RenewService::create(
            [
                'orderdetail_id' => $id,
                'email' => $user->email,
                'product_id' => $detail->product_id,
                'os_id' => $detail->os_id,
                'type' => $detail->type,
                'number' => $number,
                'price' => $price,
                'backup' => $detail->backup
            ]
        );

        return redirect()->route('customer.cart.listrenews');
    }

    public function listrenews()
    {
        $user = Auth::user();
        $page = "Gia hạn";
        $title = "Gia hạn dịch vụ";
        $listrenews = RenewService::where('email', $user->email)->get();
        $sum = RenewService::where('email', $user->email)->sum('price');
        return view('customer.extend.index', compact('listrenews', 'sum', 'page', 'title'));
    }

    public function deleteItem(Request $request)
    {
        $user = Auth::user();
        $detail = RenewService::find($request->id);
        $detail->delete();
        $sum = RenewService::where('email', $user->email)->sum('price');

        return response()->json([
            'message' => 'Xóa dịch vụ thành công!',
            'total_price' => number_format($sum, 0, ',', '.') . ' đ',
            'count' => RenewService::where('email', $user->email)->count(),
        ]);
    }

    public function updatetime(Request $request)
    {
        $user = Auth::user();
        $details = RenewService::find($request->id);
        if ($details->type == 'hosting') {
            $details->price = $details->price  / ($details->number ) * $request->quantity;
        } else {
            if ($details->backup == '0') {
                $details->price = $details->price / $details->number * $request->quantity;
            } else {
                $details->price = ($details->price - 75000) / $details->number * $request->quantity + 75000;
            }
        }
        $details->number = $request->quantity;

        $details->save();
        $sum = RenewService::where('email', $user->email)->sum('price');
        return response()->json([
            'message' => 'Cập nhật số lượng thành công!',
            'price' => number_format($details->price, 0, ',', '.') . ' đ',
            'total_price' => number_format($sum, 0, ',', '.') . ' đ',
        ]);
    }
}
