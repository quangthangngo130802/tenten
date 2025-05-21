<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\UserRequest;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $title = "Khách hàng";
        if ($request->ajax()) {

            $data = User::with(['ward1', 'district1', 'province1'])->where('role_id', '!=', 1)->select('*');
            return DataTables::of($data)
            ->addIndexColumn()
                ->addColumn('address_detail', function ($row) {
                    $address = $row->address;
                    $ward = $row->ward1 ? $row->ward1->name : '';
                    $district = $row->district1 ? $row->district1->name : '';
                    $province = $row->province1 ? $row->province1->name : '';

                    // Nối các giá trị thành một chuỗi
                    return trim("$address, $ward, $district, $province", ', ');
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="checkbox-item"  name="selected[]" value="' . $row->id . '">';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<div class="status active">
                                    <span class="icon-check"></span> Hoạt động
                                </div>';
                    } else {
                        return '<div class="status paused">
                                    <span class="icon-warning"></span> Tạm dừng
                                </div>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<div>
                                <a href="' . route('client.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
                                    <i class="fas fa-edit btn-edit" title="Sửa"></i>
                                </a>
                                <a class="btn btn-danger btn-sm delete"
                                onclick="confirmDelete(event, ' . $row->id . ')">
                                    <i class="fas fa-trash btn-delete" title="Xóa"></i>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('client.delete', $row->id) . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '

                                </form>
                            </div>';
                })->rawColumns(['action', 'status', 'checkbox'])
                ->make(true);
        }
        $page = 'Khách hàng';
        return view('backend.client.index', compact('title', 'page'));
    }

    public function create()
    {
        $province = Province::get();
        $page = 'Khách hàng';
        $title = 'Thêm Khách hàng';
        return view('backend.client.detail', compact('title', 'page', 'province'));
    }

    public function edit($id)
    {
        $province = Province::get();
        $page = 'Khách hàng';
        $title = 'Sửa Khách hàng';
        $user = User::with(['ward1', 'district1', 'province1'])->find($id);
        return view('backend.client.detail', compact('user', 'title', 'page', 'province'));
    }

    public function update(ClientRequest $request, $id)
    {
        // dd($request->toArray());
        $user = User::find($id);
        $credentials = $request->validated();
        if ($request->password) {
            $credentials['password'] = bcrypt($request->password);
        } else {
            unset($credentials['password']);
        }
        $user->update($credentials);
        toastr()->success('Cập nhật thành công.');
        return redirect()->route('client.index');
    }

    public function store(ClientRequest $request)
    {
        $credentials = $request->validated();
        $credentials['password'] = bcrypt(123456);
        User::create($credentials);
        toastr()->success('Thêm thành công.');
        return redirect()->route('client.index');
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        toastr()->success('Xóa thành công.');
        return redirect()->back();
    }

    public function deleteItemsClient(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Không có mục nào được chọn']);
        }

        try {
            User::whereIn('id', $ids)->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Xóa không thành công']);
        }
    }

}
