<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserRequest;
use App\Mail\CreateUserEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $title = "Tài khoản";
        if ($request->ajax()) {

            $data = User::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<div style="display: flex;">
                                <a href="' . route('user.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
                                    <i class="fas fa-edit btn-edit" title="Sửa"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm delete"
                                onclick="confirmDelete(event, ' . $row->id . ')">
                                    <i class="fas fa-trash btn-delete" title="Xóa"></i>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('user.delete', $row->id) . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '

                                </form>
                            </div>';
                })->rawColumns(['action'])
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
        }else{
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

        Mail::to($credentials['email'])->send(new CreateUserEmail($data));
        toastr()->success('Thêm thành công.');
        return redirect()->route('user.index');
    }
}
