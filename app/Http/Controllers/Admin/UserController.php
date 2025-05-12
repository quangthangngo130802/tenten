<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserRequest;
use App\Imports\UsersImport;
use App\Mail\CreateUserEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $title = "Tài khoản";
        if ($request->ajax()) {

            $data = User::where('role_id', [1, 3])->orderBy('updated_at', 'desc')->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
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
                    return '<div style="">
                                <a href="' . route('user.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
                                    <i class="fas fa-edit btn-edit" title="Sửa"></i>
                                </a>

                            </div>';
                })->rawColumns(['action', 'status'])
                ->make(true);
        }
        $page = 'Tài khoản';
        return view('backend.user.index', compact('title', 'page'));
    }

    public function create()
    {
        $page = 'Tài khoản';
        $title = 'Thêm Tài khoản';
        return view('backend.user.detail', compact('title', 'page'));
    }

    public function edit($id)
    {
        $page = 'Tài khoản';
        $title = 'Sửa Tài khoản';
        $user = User::find($id);
        return view('backend.user.detail', compact('user', 'title', 'page'));
    }

    public function update(UserEditRequest $request, $id)
    {
        $user = User::find($id);
        $credentials = $request->validated();
        $credentials = $request->validated();

        if (!is_null($credentials['password'] ?? null)) {
            $credentials['password'] = bcrypt($credentials['password']);
        } else {
            unset($credentials['password']);
        }

        $user->update($credentials);
        toastr()->success('Cập nhật thành công.');
        return redirect()->route('user.index');
    }

    public function store(UserRequest $request)
    {
        $credentials = $request->validated();
        if (!is_null($credentials['password'] ?? null)) {
            $credentials['password'] = bcrypt($credentials['password']);
        }
        User::create($credentials);
        $data = [
            'name' => $credentials['full_name'],
            'username' => $credentials['username'],
        ];

        Mail::to($credentials['email'])->queue(new CreateUserEmail($data));
        toastr()->success('Thêm thành công.');
        return redirect()->route('user.index');
    }


    public function import(Request $request)
    {
        // dd($request->all());
        // Kiểm tra file có hợp lệ không
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        // Import dữ liệu từ file
        Excel::import(new UsersImport, $request->file('file'));

        // Trả về thông báo thành công
        return redirect()->route('user.index')->with('success', 'Dữ liệu đã được import thành công!');
    }
}
