<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\DetailCart;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DomainController extends Controller
{
    //
    public function index()
    {
        $title = "Kiểm tra tên miền";
        $listDomain = $this->domainPrice();
        return view('customer.domain.index', compact('title', 'listDomain'));
    }

    public function domainPrice()
    {

        $url = 'https://api-reseller.tenten.vn/v1/Domains/price.json';

        $data = [
            "api_key" => "6dc564c5e650dedd67144761a3f2fcdb",
            "api_user" => "dnse002",
        ];

        try {
            $client = new Client();
            $response = $client->post($url, [
                'form_params' => $data,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            return $responseBody['data'];
            // dd($responseBody['data']);

        } catch (\Exception $e) {
            return back()->withErrors('Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }

    public function checkDomain(Request $request)
    {


        $title = 'Đăng ký tên miền';
        $url = 'https://api-reseller.tenten.vn/v1/Domains/search.json';
        $domain = $request->input('domain');
        if($domain == null){
            return redirect()->back();
        }
        $user = Auth::user();

        $cartDetails = $this->getCartDetails($user->id);
        $data = [
            "api_key" => "6dc564c5e650dedd67144761a3f2fcdb",
            "api_user" => "dnse002",
            "domainName" =>  $domain,
        ];

        try {
            $client = new Client();
            $response = $client->post($url, [
                'form_params' => $data,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody['code'] == 1000) {
                $domains  = $this->domainPrice();
                // dd($domains);

                $parts = explode('.', $domain);

                $namedomain = $parts[0];
                $cart = Cart::where('user_id', $user->id)->with('details')->first() ?? [];
                // dd($cart);
                return view('customer.domain.register', compact('domains', 'namedomain', 'title', 'cart', 'cartDetails'));
            }
        } catch (\Exception $e) {
            return back()->withErrors('Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }
    public function addToCart(Request $request)
    {
        try {
            $user = Auth::user();
            $domainExtension = $request->input('domain');
            $domainName = $request->nameDomain;
            $price = $request->price;

            // Lấy giỏ hàng hoặc tạo mới nếu chưa có
            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['total_price' => 0]
            );

            // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
            $exists = DetailCart::where('cart_id', $cart->id)
                ->where('domain', $domainName)
                ->where('domain_extension', $domainExtension)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Domain đã có trong giỏ hàng'
                ], 400);
            }

            // Cập nhật tổng tiền giỏ hàng
            $cart->increment('total_price', $price);

            // Thêm vào chi tiết giỏ hàng
            DetailCart::create([
                'cart_id' => $cart->id,
                'type' => 'domain',
                'domain' => $domainName,
                'domain_extension' => $domainExtension,
                'price' => $price,
                'number' => 12
            ]);

            return response()->json([
                'success' => true,
                'listdomain' => $this->getCartDetails($user->id),
                'total' => $cart->total_price,
                'count' => $this->getCartAll($user->id)->count()

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteToCart(Request $request)
    {
        try {
            $user = Auth::user();
            $domainExtension = $request->input('domain');
            $domainName = $request->nameDomain;

            // Tìm sản phẩm trong giỏ hàng
            $detailCart = DetailCart::whereHas('cart', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->where('domain', $domainName)
                ->where('domain_extension', $domainExtension)
                ->first();

            if (!$detailCart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Domain không tồn tại trong giỏ hàng'
                ], 400);
            }

            // Cập nhật tổng tiền giỏ hàng
            $cart = $detailCart->cart;
            $cart->decrement('total_price', $detailCart->price);

            // Xóa sản phẩm khỏi giỏ hàng
            $detailCart->delete();

            return response()->json([
                'success' => true,
                'listdomain' => $this->getCartDetails($user->id),
                'total' => $cart->total_price,
                'count' => $this->getCartAll($user->id)->count()

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hàm lấy danh sách chi tiết giỏ hàng của người dùng
     */
    private function getCartDetails($userId)
    {
        return DetailCart::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('type', 'domain')->get();
    }

    private function getCartAll($userId)
    {
        return DetailCart::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
    }


    public function delete($id)
    {
        try {
            $user = Auth::user();
            $detail = DetailCart::find($id);

            if (!$detail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng.'
                ]);
            }

            $cart = Cart::where('user_id', $user->id)->first();
            if ($cart) {
                $cart->total_price -= $detail->price;
                $cart->save();
            }

            $detail->delete();


            return response()->json([
                'success' => true,
                'listdomain' => $this->getCartDetails($user->id),
                'total' => $cart->total_price ?? 0, // Tránh lỗi nếu cart null
                'count' => $this->getCartAll($user->id)->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
