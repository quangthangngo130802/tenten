<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\DetailCart;
use App\Models\Email;
use App\Models\EmailConfigEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class EmailController extends Controller
{
    public function index(Request $request, $email_type = 1)
    {
        $title = "Danh sách Email";
        if ($request->ajax()) {
            $data = Email::select('*')->get();
            return DataTables::of($data)
                ->editColumn('price', function ($row) {
                    return number_format($row->price).'đ';
                })->rawColumns(['price'])
                ->addColumn('action', function ($row) {
                    return '<div >
                    <a data-id="' . $row->id . '" data-type="cloud" class="btn btn-primary btn-sm edit" href="' . route('customer.email.viemail', ['id' => $row->id]) . '">
                        Mua ngay
                    </a>
                </div>';
                })->rawColumns(['action'])

                ->make(true);
        }
        $page = 'Email';
        return view('customer.email.index', compact('title', 'page'));
    }

    public function viemail($id)
    {
        $page = 'Email Server';
        $title = "Tạo đơn hàng";
        $email = Email::findOrFail($id);
        $package_price = EmailConfigEmail::with('emailConfig')->where('email_id', $id)->get();
        $price_email = EmailConfigEmail::where([['email_id', '=', $id],])->first();
        // dd($price_email);
        $emaillist = Email::where('email_type', $email->email_type)->get();
        return view('customer.payment.email', compact('email', 'emaillist', 'page', 'title', 'package_price', 'price_email'));
    }

    public function addToCart(Request $request)
    {
        //    dd($request->all());
        try {

            $itemId = $request->input('product_id');
            $type = 'email';
            $quantity = $request->input('numbertg', 12);

            $user = Auth::user();

            $product = Email::find($itemId);

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

            $cart->total_price += $request->totalprice;
            $cart->save();

            DetailCart::create([
                'cart_id' => $cart->id,
                'product_id' => $itemId,
                'type' => $type,
                'domain' => $request->domain,
                'price' => $request->totalprice,
                'number' => $quantity
            ]);

            return redirect()->route('customer.cart.listcart');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function packageType(Request $request){
        if(!$request->package_type || !$request->product_key){
            return response()->json(['success' =>false]);
        }

        $email = Email::find($request->product_key);

        $package_price = EmailConfigEmail::where([
            ['email_id', '=', $request->product_key],
            ['email_config_id', '=', $request->package_type],
        ])->first();

        return response()->json(['success' =>true , 'data' => $package_price, 'email' => $email  ]);
    }
}
