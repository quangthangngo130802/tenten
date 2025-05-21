<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HostingRequest;
use App\Models\Hosting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HostingController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Danh sách Hosting";
        if ($request->ajax()) {
            $data = Hosting::select('*')
            ->orderBy('website_limit', 'asc');
            return DataTables::of($data)
            ->editColumn('price', function ($row) {
                return $row->price !== null && is_numeric($row->price)
                    ? number_format($row->price, 0, ',', '.')
                    : '0';
            })
                ->addColumn('action', function ($row) {
                    return '<div style="display: flex;">
                                <a href="' . route('hosting.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
                                    <i class="fas fa-edit btn-edit" title="Sửa"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm delete"
                                onclick="confirmDelete(event, ' . $row->id . ')">
                                    <i class="fas fa-trash btn-delete" title="Xóa"></i>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('hosting.delete', $row->id) . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '

                                </form>
                            </div>';
                })->rawColumns(['action', 'price'])
                ->make(true);
        }
        $page = 'Hosting';
        return view('backend.hosting.index', compact('title', 'page'));
    }

    public function edit($id){
        $page ='Hosting';
        $title = 'Thay đổi Hosting';
        $hosting = Hosting::findOrFail($id);
        return view('backend.hosting.detail', compact('hosting', 'title', 'page'));
    }
    public function create(){
        $page ='Hosting';
        $title = 'Thêm Hosting';
        return view('backend.hosting.detail' , compact( 'title', 'page'));
    }

    public function update(HostingRequest $request, $id)
    {
        $user = Hosting::find($id);
        $credentials = $request->validated();

        $user->update($credentials);
        toastr()->success('Cập nhật thành công.');
        return redirect()->route('hosting.index');
    }

    public function store(HostingRequest $request)
    {
        $credentials = $request->validated();
        Hosting::create($credentials);
        toastr()->success('Thêm thành công.');
        return redirect()->route('hosting.index');
    }


    public function delete($id)
    {
        $hosting = Hosting::find($id);
        $hosting->delete();
        toastr()->success('Xóa thành công.');
        return redirect()->route('cloud.index');
    }
}
