<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CloudNotify;
use App\Mail\EmailNotify;
use App\Mail\HostingNotify;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Đơn hàng";

        if ($request->ajax()) {
            $data = Order::where('status', $status)->orderBy('created_at', 'desc')->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('code', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class=" text-primary "> ' . $row->code . '</a>';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 'payment'
                        ? '<span style="color: orange;">Đã thanh toán</span>'
                        : ($row->status == 'active'
                            ? '<span style="color: green;">Đã duyệt</span>'
                            : ($row->status == 'pending'
                                ? '<span style="color: blue;">Đang chờ cấp tài khoản</span>'
                                : '<span style="color: red;">Chưa thanh toán</span>'));
                })
                ->editColumn('payment', function ($row) {
                    return number_format($row->payment);
                })
                // ->editColumn('detail', function ($row) {
                //     return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Chi tiết </a>';
                // })->rawColumns(['detail'])
                ->addColumn('action', function ($row) {
                    return $row->status == 'payment'
                        ? '<div style="display: flex;">
                            <a href="#" class="btn btn-orange btn-sm delete"
                                onclick="confirmActive(event, ' . $row->id . ')">
                               Duyệt
                            </a>
                            <form id="active-form-' . $row->id . '" action="' . route('order.active', $row->id) . '" method="POST" style="display:none;">
                                ' . csrf_field() . '
                            </form>
                        </div>'
                        : ($row->status == 'pending'
                            ? '<div style="display: flex;">
                                    <a href="#" class="btn btn-orange btn-sm delete"
                                        onclick="confirmActive(event, ' . $row->id . ')">
                                    Cấp tài khoản
                                    </a>
                                    <form id="active-form-' . $row->id . '" action="' . route('order.active', $row->id) . '" method="POST" style="display:none;">
                                        ' . csrf_field() . '
                                    </form>
                                </div>'
                            : '<div style="display: flex;">
                                <a href="#" class="btn btn-danger btn-sm delete"
                                    onclick="confirmDelete(event, ' . $row->id . ')">
                                    <i class="fas fa-trash btn-delete" title="Xóa"></i>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('order.delete', $row->id) . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '
                                </form>
                            </div>');
                })->rawColumns(['action', 'status', 'code'])
                ->make(true);
        }
        $page = 'Đơn hàng';
        return view('backend.order.index', compact('title', 'page'));
    }

    public function show($id)
    {
        $title = "Chi tiết đơn hàng";
        $page = "Đơn hàng";
        $order = Order::findOrFail($id);
        return view('backend.order.show', compact('order', 'title', 'page'));
    }

    public function delete($id)
    {
        $order = Order::find($id);
        $order->orderDetail()->delete();
        $order->delete();
        return redirect()->back()->with('success', 'Đơn hàng đã xóa thành công');
    }

    public function active($id)
    {
        $order = Order::find($id);
        if ($order->order_type == 2) {
            $renewService = $order->orderDetail;
            $renewService->each(function ($service) {
                $service = Service::find($service->service_id);
                $service->update([
                    'price' => $service->price + $service->price,
                    'number' => $service->number + $service->number
                ]);

            });
            $order->update([
                'status' => 'active',
                'active_at' => now(),
            ]);

            $order->orderDetail()->update([
                'status' => 'active',
                'active_at' => now(),
            ]);
            return redirect()->route('order.show', ['id' => $id])->with('success', 'Đơn hàng đã được kích hoạt');
        }

        $order->update([
            'status' => 'pending',
            'active_at' => now(),
        ]);

        $order->orderDetail()->update([
            'status' => 'pending',
            'active_at' => now(),
        ]);
        return redirect()->route('order.show', ['id' => $id])->with('success', 'Đơn hàng đã được duyệt chờ cấp tài khoản');
    }

    public  function createAccount(Request $request, $id)
    {
        $orderDetail = OrderDetail::find($id);
        // dd($orderDetail);

        Service::create([
            'email' => $orderDetail->order->email ?? null,
            'product_id' => $orderDetail->product_id,
            'os_id' => $orderDetail->os_id,
            'type' => $orderDetail->type,
            'domain' => $orderDetail->domain,
            'domain_extension' => $orderDetail->domain_extension,
            'price' => $orderDetail->price,
            'time_type' => $orderDetail->time_type,
            'number' => $orderDetail->number,
            'backup' => $orderDetail->backup,
            'status' => 'active',
            'active_at' => now(),
            'content' => $request->content,

        ]);
        $orderDetail->update([
            'status' => 'active'
        ]);

        $order = $orderDetail->order;
        if ($order->orderDetail()->where('status', '!=', 'active')->doesntExist()) {
            $order->update(['status' => 'active']);
        }
        $user = User::where('email', $order->email)->first();

        $data = [
            'content' => $request->content,
            'name' => $user->full_name,
            'email' => $user->email
        ];
        if ($orderDetail->type == 'email') {
            Mail::to($orderDetail->order->email)->send(new EmailNotify($data));
        } else if ($orderDetail->type == 'cloud') {
            Mail::to($orderDetail->order->email)->send(new CloudNotify($data));
        } else if ($orderDetail->type == 'hosting') {
            Mail::to($orderDetail->order->email)->send(new HostingNotify($data));
        }
        return redirect()->back()->with('success', 'Cấp tài khoản thành công');
    }
}
