<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailRequest;
use App\Models\Email;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        $title = "Danh sách Email";
        if ($request->ajax()) {
            $data = Email::select('*');
            return DataTables::of($data)
                ->editColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->addColumn('action', function ($row) {
                    return '<div>
                                <a href="' . route('email.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
                                    <i class="fas fa-edit btn-edit" title="Sửa"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm delete"
                                onclick="confirmDelete(event, ' . $row->id . ')">
                                    <i class="fas fa-trash btn-delete" title="Xóa"></i>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('email.delete', $row->id) . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '

                                </form>
                            </div>';
                })->rawColumns(['action'])
                ->make(true);
        }
        $page = 'Email';
        return view('backend.email.index', compact('title', 'page'));
    }

    public function edit($id){
        $page ='Email';
        $title = 'Thay đổi Email';
        $email = Email::findOrFail($id);
        return view('backend.email.detail', compact('email', 'title', 'page'));
    }
    public function create(){
        $page ='Email';
        $title = 'Thêm Email';
        return view('backend.email.detail' , compact( 'title', 'page'));
    }

    public function update(EmailRequest $request, $id)
    {
        $email = Email::find($id);
        $credentials = $request->validated();

        $email->update($credentials);
        toastr()->success('Cập nhật thành công.');
        // dd(route('email.index', ['type_id' => $email->email_type]));
        return redirect()->route('email.index', ['type_id' => $email->email_type]);
    }

    public function store(EmailRequest $request)
    {
        $credentials = $request->validated();
        Email::create($credentials);
        toastr()->success('Thêm thành công.');
        return redirect()->route('email.index', ['type_id' => $request->email_type]);
    }


    public function delete($id)
    {
        $email = Email::find($id);
        $email->delete();
        toastr()->success('Xóa thành công.');
        return redirect()->route('email.index', ['type_id' => $email->email_type]);
    }
}
