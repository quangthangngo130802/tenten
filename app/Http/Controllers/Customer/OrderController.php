<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\RenewService;
use App\Models\TransactionHistory;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Danh sách đơn hàng";

        if ($request->ajax()) {
            $data = Order::where('status', $status)->where('email', Auth::user()->email)->select('*');
            return DataTables::of($data)
            ->addIndexColumn()
                ->editColumn('code', function ($row) {
                    return '<a href="' . route('customer.order.show', $row->id) . '" class=" text-primary "> ' . $row->code . '</a>';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d-m-Y');
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 'payment'
                        ? '<span style="color: orange;">Đã thanh toán</span>'
                        : ($row->status == 'active'
                            ? '<span style="color: green;">Đã thanh toán</span>'
                            : '<span style="color: red;">Chưa thanh toán</span>');
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->editColumn('payment', function ($row) {
                    return $row->status == 'payment'
                        ? '<span style="color: orange;">Chưa kích hoạt</span>'
                        : ($row->status == 'active'
                            ? '<span style="color: green;">Đã kích hoạt</span>'
                            : ($row->order_type == 2
                                ? '<a href="' . route('customer.order.renew.payment', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm">Thanh toán Gia hạn </a>'
                                : '<a href="' . route('customer.order.payment', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm">Thanh toán</a>'));
                })
                ->editColumn('detail', function ($row) {
                    return '<a href="' . route('customer.order.show', $row->id) . '" class=" btn-sm edit"> Chi tiết </a>';
                })->rawColumns(['detail'])
                ->rawColumns(['detail', 'status', 'payment', 'code'])
                ->make(true);
        }
        $page = 'Đơn hàng';
        return view('customer.order.index', compact('title', 'page'));
    }

    public function show($id)
    {
        $title = "Chi tiết đơn hàng";
        $page = "Đơn hàng";
        $order = Order::findOrFail($id);
        return view('customer.order.show', compact('order', 'title', 'page'));
    }

    public function payment(Request $request)
    {

        /**
         * @var User $user
         */
        $user = Auth::user();
        $order = Order::find($request->id);

        if ($user->wallet < $order->amount) {
            return response()->json([
                'error' => 'Tài khoản không đủ để thanh toán!',
            ], 400);
        }
        $user->update([
            'wallet' => $user->wallet - $order->amount,
        ]);
        $order->orderDetail()->update(['active' => 'payment']);
        $order->status = 'payment';
        $order->save();

        TransactionHistory::create([
            'code' => $order->code,
            'user_id' => $user->id,
            'type' => 'Thanh toán',
            'amount' => $order->amount,
            'status' => 2,
            'type' => 2, /// 1 chưa, 2 thanh toán, 3 duỵet
            'description' => 'Thanh toán đơn hàng #' . $order->code,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thanh toán thành công!',
        ]);
    }


    public function addorder($id = null)
    {
        $page = 'Đơn hàng';
        $title = 'Thanh toán đơn hàng';;

        if ($id == null) {
            $cart = Cart::first();

            if (!$cart) {
                $order_new = Order::latest()->first();
                return view('customer.cart.payment', compact('order_new'));
            }

            $order = Order::create([
                'code' => Str::random(15),
                'email' => Auth::user()->email,
                'fullname' => Auth::user()->full_name,
                'amount' => $cart->total_price,
                'status' => 'nopayment',
                'payment' => 0,
                'order_type' => 1,
                'vat' => vat_rate()
            ]);


            $cart->details->each(function ($detail) use ($order) {
                $order->orderDetail()->create([
                    'order_id' => $order->id,
                    'product_id' => $detail->product_id,
                    'domain' => $detail->domain,
                    'os_id' => $detail->os_id,
                    'type' => $detail->type,
                    'price' => $detail->price,
                    'backup' => $detail->backup,
                    'number' => $detail->number,
                    'domain_extension' => $detail->domain_extension

                ]);
                $detail->delete(); // Xóa chi tiết sau khi đã thêm vào order
            });

            // Xóa cart sau khi tạo đơn hàng
            $cart->delete();

            // Lấy đơn hàng mới nhất (order)
            $order_new = Order::find($order->id);
        } else {
            $order_new = Order::find($id);
        }
        // Trả về view với order_new
        return view('customer.cart.payment', compact('order_new', 'page', 'title'));
    }


    public function thanhtoan(Request $request)
    {

        $user = Auth::user();
        /**
         * @var User $user
         */

        $price = $request->input('price');
        $order = Order::find($request->id);

        if ($user->wallet >= $price) {
            $amount = $order->amount;
            $order->status = 'payment';
            $order->vat = vat_rate();
            $order->save();
            $user->update([
                'wallet' => $user->wallet - $price,
            ]);
            TransactionHistory::create([
                // 'code' => Str::random(10),
                'user_id' => $user->id,
                'amount' =>  $price,
                'status' => 1,
                'type' => 2,
                'description' => 'Thanh toán đơn hàng ' . $order->code,
            ]);
            if ($request->invoice == 'yes') {
                $thongtin = $request->thongtin;
                // Tạo PDF từ view
                $pdf = PDF::loadView('pdf.receipt', compact('order', 'user', 'price', 'thongtin', 'vat'));

                // Thiết lập các tùy chọn
                $pdf->setOption('encoding', 'UTF-8');
                $pdf->setOption('defaultFont', 'Noto Sans');

                // Đường dẫn để lưu PDF
                $pdfPath = storage_path('app/public/receipts/receipt_order_' . $order->id . '.pdf');

                // Lưu PDF
                $pdf->save($pdfPath);

                // Tạo URL để tải PDF
                $pdfUrl = asset('storage/receipts/receipt_order_' . $order->id . '.pdf');

                return response()->json([
                    'success' => true,
                    'pdf_url' => $pdfUrl
                ]);
            } else {
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['success' => false]);
        }
    }


    public function createPayment($id, $xsd)
    {
        // Kiểm tra số tiền
        $order_new = Order::find($id);
        $amount = $order_new->amount;
        $vat = vat_amount($amount);
        $amount_pay = $amount + $vat;
        // Còn lại xử lý yêu cầu tạo thanh toán như hiện tại
        $clientId = env('PAYOS_CLIENT_ID');
        $apiKey = env('PAYOS_API_KEY');
        $checksumKey = env('PAYOS_CHECKSUM_KEY');
        $orderCode = substr(str_shuffle("0123456789"), 0, 11);

        $data = [
            "orderCode" => intval($orderCode),
            "amount" => intval($amount_pay),
            "description" => "VQRIO123",
            "cancelUrl" => route('customer.order.payment'),
            "returnUrl" => route('customer.order.return', ['id' => $id, 'xsd' => $xsd]),
            "expiredAt" => time() + 3600,
        ];

        $data['signature'] = $this->createSignatureOfPaymentRequest($checksumKey, $data);

        $client = new Client();

        try {
            $response = $client->post('https://api-merchant.payos.vn/v2/payment-requests', [
                'headers' => [
                    'x-client-id' => $clientId,
                    'x-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if (isset($responseBody['data']['checkoutUrl'])) {
                $paymentLink = $responseBody['data']['checkoutUrl'];
                return redirect()->to($paymentLink);
            }
            return response()->json([
                'success' => $responseBody,
                'status_code' => $response->getStatusCode(),
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function createSignatureOfPaymentRequest($checksumKey, $obj)
    {
        $dataStr = "amount={$obj["amount"]}&cancelUrl={$obj["cancelUrl"]}&description={$obj["description"]}&orderCode={$obj["orderCode"]}&returnUrl={$obj["returnUrl"]}";
        $signature = hash_hmac("sha256", $dataStr, $checksumKey);

        return $signature;
    }

    public function cancelUrl()
    {
        toastr()->error('Đã hủy giao dịch.');
        return redirect()->route('payment.recharge');
    }
    public function returnUrl($id, $xsd)
    {
        Log::info($xsd);
        $user = Auth::user();
        /**
         * @var User $user
         */
        $order = Order::find($id);
        $amount = $order->amount;
        $order->status = 'payment';
        $order->save();

        TransactionHistory::create([
            'code' => Str::random(10),
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => 1,
            'type' => 2,
            'description' => 'Thanh toán đơn hàng ' . $order->code,
        ]);

        if ($xsd == 'yes') {
            $pdf = PDF::loadView('pdf.receipt');

            // Thiết lập các tùy chọn
            $pdf->setOption('encoding', 'UTF-8');
            $pdf->setOption('defaultFont', 'Noto Sans');

            // Đường dẫn để lưu PDF
            $pdfPath = storage_path('app/public/receipts/receipt_order_' . $order->id . '.pdf');

            // Lưu PDF
            $pdf->save($pdfPath);

            // Tạo URL để tải PDF
            $pdfUrl = asset('storage/receipts/receipt_order_' . $order->id . '.pdf');
            session()->put('pdfContent', $pdfUrl);
            toastr()->success('Giao dịch thành công.');
            // Redirect đến trang chi tiết đơn hàng
            return redirect()->route('customer.order.show', ['id' => $id]);
        }



        return redirect()->route('customer.order.show', ['id' => $id]);
    }


    public function clearPdfSession(Request $request)
    {
        // Xóa session pdfContent
        session()->forget('pdfContent');

        return response()->json(['success' => true]);
    }


    public function createPaymentenews($id = null)
    {
        // Kiểm tra số tiền
        // $order_new = Order::find($id);
        // $amount = $order_new->amount;
        // dd($id);
        $amount = 0;
        $user = Auth::user();
        if($id != null){
                $order = Order::find($id);
                $amount = $order->amount;
                $cancelUrl = route('customer.order.renew.payment', ['id' => $id]);
                $returnUrl = route('customer.order.create.payment.enews.success', ['id' => $id]);
        }else{
            $amount = RenewService::where('email', $user->email)->sum('price');

            $renewservices = RenewService::where('email', $user->email)->get();

            $order = Order::create([
                'code' => Str::random(15),
                'email' => Auth::user()->email,
                'fullname' => Auth::user()->full_name,
                'amount' => $amount,
                'status' => 'nopayment',
                'payment' => 0,
                'order_type' => 2
            ]);

            $renewservices->each(function ($detail) use ($order) {
                $order->orderDetail()->create([
                    'order_id' => $order->id,
                    'product_id' => $detail->product_id,
                    'domain' => $detail->domain,
                    'domain_extension' => $detail->domain_extension,
                    'os_id' => $detail->os_id,
                    'type' => $detail->type,
                    'price' => $detail->price,
                    'backup' => $detail->backup,
                    'number' => $detail->number,
                    'service_id' => $detail->service_id
                ]);
                $detail->delete(); // Xóa chi tiết sau khi đã thêm vào order
            });
            $cancelUrl = route('customer.order.renew.payment', ['id' => $order->id]);
            $returnUrl = route('customer.order.create.payment.enews.success', ['id' => $order->id]);
        }


        // Còn lại xử lý yêu cầu tạo thanh toán như hiện tại
        $clientId = env('PAYOS_CLIENT_ID');
        $apiKey = env('PAYOS_API_KEY');
        $checksumKey = env('PAYOS_CHECKSUM_KEY');
        $orderCode = substr(str_shuffle("0123456789"), 0, 11);

        $data = [
            "orderCode" => intval($orderCode),
            "amount" => intval($amount),
            "description" => "VQRIO123",
            "cancelUrl" => $cancelUrl,
            "returnUrl" => $returnUrl ,
            "expiredAt" => time() + 3600,
        ];

        $data['signature'] = $this->createSignatureOfPaymentRequest($checksumKey, $data);

        $client = new Client();

        try {
            $response = $client->post('https://api-merchant.payos.vn/v2/payment-requests', [
                'headers' => [
                    'x-client-id' => $clientId,
                    'x-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if (isset($responseBody['data']['checkoutUrl'])) {
                $paymentLink = $responseBody['data']['checkoutUrl'];
                return redirect()->to($paymentLink);
            }
            return response()->json([
                'success' => $responseBody,
                'status_code' => $response->getStatusCode(),
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function paymentenewsSuccess($id)
    {

        $user = Auth::user();
        /**
         * @var User $user
         */
        // $amount = RenewService::where('email', $user->email)->sum('price');
        RenewService::where('email', $user->email)->delete();

        $order = Order::find($id);
        $user->update([
            'wallet' => $user->wallet - $order->amount,
        ]);
        $order->update(
           [
            'status' => 'payment'
           ]
           );
        return redirect()->route('customer.service.hosting.list.hosting');
    }

    public function thanhtoangiahan($id = null)
    {
        $user = Auth::user();
        /**
         * @var User $user
         */

        if ($id != null) {
            $order = Order::find($id);
            if ($user->wallet >= $order->amount) {
                $user->update([
                    'wallet' => $user->wallet - $order->amount,
                ]);
                TransactionHistory::create([
                    'code' => Str::random(10),
                    'user_id' => $user->id,
                    'amount' => $order->amount,
                    'status' => 1,
                    'type' => 2,
                    'description' => 'Thanh toán gia hạn ',
                ]);
                $order->status = 'payment';
                $order->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } else {

            $price =  RenewService::where('email', $user->email)->sum('price');

            if ($user->wallet >= $price) {
                $user->update([
                    'wallet' => $user->wallet - $price,
                ]);
                TransactionHistory::create([
                    'code' => Str::random(10),
                    'user_id' => $user->id,
                    'amount' => $price,
                    'status' => 1,
                    'type' => 2,
                    'description' => 'Thanh toán gia hạn ',
                ]);
                $renewService = RenewService::where('email', $user->email)->get();
                $order = Order::create([
                    'code' => Str::random(15),
                    'email' => Auth::user()->email,
                    'fullname' => Auth::user()->full_name,
                    'amount' => $price,
                    'status' => 'payment',
                    'payment' => 0,
                    'order_type' => 2
                ]);

                $renewService->each(function ($detail) use ($order) {
                    $order->orderDetail()->create([
                        'order_id' => $order->id,
                        'product_id' => $detail->product_id,
                        'domain' => $detail->domain,
                        'domain_extension' => $detail->domain_extension,
                        'os_id' => $detail->os_id,
                        'type' => $detail->type,
                        'price' => $detail->price,
                        'backup' => $detail->backup,
                        'number' => $detail->number,
                        'service_id' => $detail->service_id
                    ]);
                    $detail->delete(); // Xóa chi tiết sau khi đã thêm vào order
                });
                RenewService::where('email', $user->email)->delete();

                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    public function renewaddorder($id)
    {
        $order = Order::find($id);
        $listrenews = $order->orderDetail;
        $sum = $order->amount;
        return view('customer.extend.index', compact('listrenews', 'sum', 'id'));
    }
}
