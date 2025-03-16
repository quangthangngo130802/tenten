<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Cloud;
use App\Models\DetailCart;
use App\Models\Os;
use App\Models\RenewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CloudController extends Controller
{
    public function index(Request $request, $type_id = 1)
    {
        $title = "Danh sách Cloud";
        if ($request->ajax()) {
            $data = Cloud::where('type_id', $type_id)->select('*');
            return DataTables::of($data)
                ->editColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->editColumn('total_cost', function ($row) {
                    return number_format($row->total_cost);
                })
                ->addColumn('action', function ($row) {
                    return '<div >
                                <a data-id="' . $row->id . '" data-type="cloud" class="btn btn-primary btn-sm edit" href="' . route('customer.cloud.vicloud', ['id' => $row->id]) . '">
                                    Mua ngay
                                </a>
                            </div>';
                })->rawColumns(['action'])
                ->make(true);
        }
        $page = 'Cloud Server';
        return view('customer.cloud.index', compact('title', 'page'));
    }

    public function vicloud($id)
    {
        $page = 'Cloud Server';
        $title = "Tạo đơn hàng";
        $cloud = Cloud::findOrFail($id);
        $cloudlist = Cloud::where('type_id', $cloud->type_id)->get();
        $os = Os::get();
        return view('customer.payment.cloud', compact('cloud', 'os', 'cloudlist', 'page', 'title'));
    }

    // public function addtocart(Request $request){
    //     dd($request->all());
    // }

    public function addToCart(Request $request)
    {
        //   dd($request->all());
        try {

            $itemId = $request->input('product_id');
            $type = 'cloud';
            $quantity = $request->input('numbertg', 1);

            $user = Auth::user();
            $renewservice = RenewService::where('email', $user->email)->get();
            if ($renewservice) {
                RenewService::where('email', $user->email)->delete();
            }

            $product = Cloud::find($itemId);

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

            $detailCart = DetailCart::where('cart_id', $cart->id)
                ->where('product_id', $itemId)
                ->where('type', $type)
                ->first();

            DetailCart::create([
                'cart_id' => $cart->id,
                'product_id' => $itemId,
                'type' => $type,
                'os_id' => $request->os_id,
                'backup' => $request->issetbackup,
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
}
