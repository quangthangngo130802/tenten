<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\UserZalo;
use App\Models\ZaloOa;
use App\Models\ZnsMessage;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ZaloOaController extends Controller
{
    public function oaIndex(Request $request)
    {
        $title = "Danh sách Zalo OA";

        if ($request->ajax()) {
            $data = ZaloOa::withCount('znsMessages')
                ->orderBy('created_at', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('total_messages', function ($row) {
                    return $row->zns_messages_count;
                })
                ->addColumn('owner', function ($row) {

                    return optional($row->userZalo)->name ?? '(Chưa gán)';
                })
                ->addColumn('phone', function ($row) {
                    return optional($row->userZalo)->phone ?? '(Chưa có)';
                })
                ->filterColumn('owner', function ($query, $keyword) {
                    $query->whereHas('userZalo', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%$keyword%");
                    });
                })
                ->filterColumn('phone', function ($query, $keyword) {
                    $query->whereHas('userZalo', function ($q) use ($keyword) {
                        $q->where('phone', 'like', "%$keyword%");
                    });
                })
                ->rawColumns(['total_messages', 'owner', 'phone'])
                ->make(true);
        }

        $page = 'Zalo OA';
        return view('backend.zalo.zaloOa', compact('title', 'page'));
    }

    public function userIndex(Request $request)
    {
        $title = "Danh sách khách hàng";
        if ($request->ajax()) {
            $data = UserZalo::orderBy('created_at', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('wallet', function ($row) {
                    return number_format($row->wallet, 0, ',', '.');
                })
                ->addColumn('sub_wallet', function ($row) {
                    return number_format($row->sub_wallet, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {

                    return '
                    <a  class="btn btn-sm btn-info view-user"
                        data-id="' . $row->id . '"
                        data-prefix="' . $row->prefix . '"
                        data-name="' . $row->name . '"
                        data-phone="' . $row->phone . '"
                        data-email="' . $row->email . '"
                        data-username="' . $row->username . '"
                        data-address="' . $row->address . '"
                        data-company_name="' . $row->company_name . '"
                        data-tax_code="' . $row->tax_code . '"
                        data-store_name="' . $row->store_name . '"
                        data-field="' . $row->field . '"
                        data-wallet="' . number_format($row->wallet, 0, ',', '.') . 'đ' . '"
                        data-sub_wallet="' . number_format($row->sub_wallet, 0, ',', '.') . 'đ' . '"

                        title="Xem"><i class="fas fa-eye"></i>
                    </a>
                    <a  class="btn btn-sm btn-warning edit-user"
                        data-id="' . $row->id . '"
                        data-prefix="' . $row->prefix . '"
                        data-name="' . $row->name . '"
                        data-phone="' . $row->phone . '"
                        data-email="' . $row->email . '"
                        data-username="' . $row->username . '"
                        data-address="' . $row->address . '"
                        data-company_name="' . $row->company_name . '"
                        data-tax_code="' . $row->tax_code . '"
                        data-store_name="' . $row->store_name . '"
                        data-field="' . $row->field . '"

                        data-wallet="' . number_format($row->wallet, 0, ',', '.') . '"
                        data-sub_wallet="' . number_format($row->sub_wallet, 0, ',', '.') . '"
                        title="Sửa"><i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-danger delete-user" data-id="' . $row->id . '" data-name="' . e($row->name) . '" title="Xoá">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                ';
                })->rawColumns(['action'])
                ->make(true);
        }

        $page = 'Khách hàng';
        return view('backend.zalo.user', compact('title', 'page'));
    }

    public function userAdd(Request $request)
    {

        $data = $request->except('_token');
        // dd($data);
        $data['wallet'] = (int)str_replace('.', '', $request->wallet);
        $data['sub_wallet'] = (int)str_replace('.', '', $request->sub_wallet);
        $data['role_id'] = 1;
        $data['password'] = Hash::make($request->password);
        $url = config('services.zalo.base_url') . '/api/add-user/' . $request->id;

        $client = new Client();


        try {
            $response = $client->post($url, [
                'form_params' => $data,
            ]);

            if ($response->getStatusCode() != 200) {
                Log::error('Failed to update user on API', ['status' => $response->getStatusCode()]);
                throw new Exception('Failed to update this user on remote API');
            } else {
                Log::info('User updated successfully on API');
                UserZalo::create($data);
            }
        } catch (Exception $e) {
            Log::error('Exception when updating user: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi cập nhật qua API'], 500);
        }

        return response()->json(['message' => 'Thêm thành công']);
    }

    public function userUpdate(Request $request)
    {
        $user = UserZalo::findOrFail($request->id);

        $url = config('services.zalo.base_url') . '/api/update-user/' . $request->id;

        $client = new Client();

        $wallet = preg_replace('/[^\d]/', '', $request->wallet);
        $sub_wallet = preg_replace('/[^\d]/', '', $request->sub_wallet);

        if (empty($wallet)) {
            $wallet = 0;
        }

        if (empty($sub_wallet)) {
            $sub_wallet = 0;
        }

        $data = [
            'prefix'        => $request->prefix,
            'name'          => $request->name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'username'      => $request->username,
            'address'       => $request->address,
            'company_name'  => $request->company_name,
            'tax_code'      => $request->tax_code,
            'store_name'    => $request->store_name,
            'field'         => $request->field,
            'wallet'        => $wallet,
            'sub_wallet'    => $sub_wallet,
        ];

        Log::info('Sending update to API:', $data);

        try {
            $response = $client->put($url, [
                'form_params' => $data,
            ]);

            if ($response->getStatusCode() != 200) {
                Log::error('Failed to update user on API', ['status' => $response->getStatusCode()]);
                throw new Exception('Failed to update this user on remote API');
            } else {
                Log::info('User updated successfully on API');
            }
        } catch (Exception $e) {
            Log::error('Exception when updating user: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi cập nhật qua API'], 500);
        }

        $user->update(array_merge($data, [
            'wallet'     => $wallet,
            'sub_wallet' => $sub_wallet,
        ]));

        return response()->json(['message' => 'Cập nhật thành công']);
    }


    public function userDelete(Request $request)
    {
        $url = config('services.zalo.base_url') . '/api/delete-user/' . $request->id;

        $client = new Client();
        try {
            $response = $client->post($url);

            if ($response->getStatusCode() != 200) {
                Log::error('Failed to delete user on API', ['status' => $response->getStatusCode()]);
                throw new Exception('Failed to delete this user on remote API');
            } else {
                Log::info('User deleted successfully on API');
            }
        } catch (Exception $e) {
            Log::error('Exception when deleting user: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi xoá qua API'], 500);
        }

        $user = UserZalo::findOrFail($request->id);
        $user->delete();

        return response()->json(['message' => 'Xóa thành công']);
    }


    public function messagesIndex(Request $request)
    {
        $title = "Danh sách khách hàng";
        if ($request->ajax()) {
            $data = ZnsMessage::orderBy('created_at', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('sent_at', function ($row) {
                    return Carbon::parse($row->sent_at)->format('d/m/Y H:i');
                })
                ->editColumn('status', function ($row) {
                    switch ($row->status) {
                        case 1:
                            return '<span class="badge bg-success">Thành công</span>';
                        case 0:
                            return '<span class="badge bg-danger">Thất bại</span>';
                        case 2:
                            return '<span class="badge bg-warning text-dark">Đang xử lý</span>';
                        default:
                            return '<span class="badge bg-secondary">Không rõ</span>';
                    }
                })
                ->addColumn('oa', function ($row) {
                    return $row->zaloOa->name;
                })->rawColumns(['oa', 'status'])

                ->make(true);
        }

        $page = 'Khách hàng';
        return view('backend.zalo.messages', compact('title', 'page'));
    }

    public function transactionIndex(Request $request)
    {
        $title = "Lịch sử giao dịch";
        if ($request->ajax()) {
            $data = Transaction::orderBy('created_at', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->filterColumn('user_info', function ($query, $keyword) {
                    $query->whereHas('zaloUser', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('user_info', function ($row) {
                    return optional($row->zaloUser)->name ?? '(Không có)';
                })
                ->editColumn('status', function ($row) {
                    switch ($row->status) {
                        case 0:
                            return '<span class="badge bg-success">Thành công</span>';
                        case 2:
                            return '<span class="badge bg-danger">Từ chối</span>';
                        case 1:
                            return '<span class="badge bg-warning text-dark">Đang xử lý</span>';
                        default:
                            return '<span class="badge bg-secondary">Không rõ</span>';
                    }
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount, 0, ',', '.') . 'đ';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y H:i');
                })
                ->addColumn('action', function ($row) {
                    switch ($row->status) {
                        case 0:
                            return '<span class="badge bg-success p-2">Thành công</span>';
                        case 2:
                            return '<span class="badge bg-danger" style="padding:7px 18px !important">Từ chối</span>';
                        case 1:
                            return '
                            <div class="gap-2">
                                <button class="btn btn-sm btn-success btn-action" data-id="' . $row->id . '" data-status="0">Xác nhận</button>
                                <button class="btn btn-sm btn-danger btn-action" data-id="' . $row->id . '" data-status="2">Từ chối</button>
                            </div>
                        ';
                        default:
                            return '';
                    }
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $page = 'Giao dịch ';
        return view('backend.zalo.transaction', compact('title', 'page'));
    }

    public function updateStatus(Request $request)
    {

        $request->validate([
            'id' => 'required|integer|exists:transactions,id',
            'status' => 'required|in:0,2',
        ]);

        $item = Transaction::findOrFail($request->id);

        if (in_array($item->status, [0, 2])) {
            return response()->json(['message' => 'Không thể cập nhật trạng thái.'], 400);
        }

        $url = config('services.zalo.base_url') . '/api/confirm-transaction/' . $item->id;

        $response = Http::post($url, [
            'status' => $request->status,
            'user_id' => $item->user_id,
            'amount' => $item->amount,
            'notification' => $request->status,
        ]);

        $zalo_user = UserZalo::find($item->user_id);
        $zalo_user->wallet += $item->amount;
        $zalo_user->save();

        if ($response->failed()) {
            return response()->json([
                'message' => 'Gọi API thất bại.',
                'api_status' => $response->status(),
                'api_body' => $response->body(),
            ], 500);
        }

        $item->status = $request->status;
        $item->save();

        return response()->json(['message' => 'Cập nhật trạng thái thành công.']);
    }

    public function dashboard()
    {
        $title = "Dashboard Zalo OA";
        $page = 'Dashboard';
        $filter = 'today';

        return view('backend.zalo.dashboard', [
            'title' => $title,
            'page' => $page,
            'messageSummary' => $this->messageSummary($filter),
            'summary' => $this->summary($filter),
            'userCount' => $this->userCount($filter),
            'users' => $this->user($filter),
            'zaloOa' => $this->zaloOa($filter),
            'chart' => $this->chart($filter),
        ]);
    }

    public function filterDashboard(Request $request)
    {
        $filter = $request->input('filter', 'today');
        $from = $request->input('from');
        $to = $request->input('to');

        return response()->json([
            'messageSummary' => $this->messageSummary($filter, $from, $to),
            'summary' => $this->summary($filter, $from, $to),
            'userCount' => $this->userCount($filter, $from, $to),
            'users' => $this->user($filter, $from, $to),
            'zaloOa' => $this->zaloOa($filter, $from, $to),
            'chart' => $this->chart($filter, $from, $to),
        ]);
    }


    public function messageSummary($filter, $from = null, $to = null)
    {
        [$start, $end] = $this->parseDateRange($filter, $from, $to);

        $success = ZnsMessage::where('status', 1)->whereBetween('created_at', [$start, $end])->count();
        $fail = ZnsMessage::where('status', 0)->whereBetween('created_at', [$start, $end])->count();

        $totalAmount = ZnsMessage::where('status', 1)
            ->whereBetween('created_at', [$start, $end])
            ->with('template')
            ->get()
            ->sum(fn ($m) => $m->template->price ?? 0);

        return compact('success', 'fail', 'totalAmount');
    }

    public function summary($filter, $from = null, $to = null)
    {
        [$start, $end] = $this->parseDateRange($filter, $from, $to);

        return Transaction::where('status', 'success')->whereBetween('created_at', [$start, $end])->sum('amount');
    }

    public function userCount($filter, $from = null, $to = null)
    {
        [$start, $end] = $this->parseDateRange($filter, $from, $to);
        return UserZalo::whereBetween('created_at', [$start, $end])->count();
    }

    public function user($filter, $from = null, $to = null)
    {
        [$start, $end] = $this->parseDateRange($filter, $from, $to);

        return UserZalo::withCount(['znsMessages' => function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end]);
        }])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function zaloOa($filter, $from = null, $to = null)
    {
        [$start, $end] = $this->parseDateRange($filter, $from, $to);
        return ZaloOa::withCount(['znsMessages' => function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end]);
        }])
            ->with('userZalo')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function chart($filter, $from = null, $to = null)
    {
        [$start, $end] = $this->parseDateRange($filter, $from, $to);;

        $data = ZnsMessage::selectRaw("DATE(created_at) as date,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as success,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as fail")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $successData = [];
        $failData = [];

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->format('Y-m-d');
            $labels[] = $d->format('d/m');
            $successData[] = $data[$key]->success ?? 0;
            $failData[] = $data[$key]->fail ?? 0;
        }

        return compact('labels', 'successData', 'failData');
    }

    private function parseDateRange($filter, $from = null, $to = null)
    {
        $now = Carbon::now();
        $start = Carbon::today();
        $end = $now;

        switch ($filter) {
            case 'yesterday':
                $start = Carbon::yesterday()->startOfDay();
                $end = Carbon::yesterday()->endOfDay();
                break;
            case 'last_7_days':
                $start = $now->copy()->subDays(6)->startOfDay();
                $end = $now->endOfDay();
                break;
            case 'custom':
                $start = Carbon::parse($from)->startOfDay();
                $end = Carbon::parse($to)->endOfDay();
                break;
        }

        return [$start, $end];
    }
}
