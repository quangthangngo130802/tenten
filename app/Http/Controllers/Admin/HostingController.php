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
        $title = "Hosting";
        if ($request->ajax()) {
            $data = Hosting::select('*');
            return DataTables::of($data)

                ->addColumn('action', function ($row) {
                    return '<div style="display: flex;">
                                <a href="' . route('hosting.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
                                    <i class="fas fa-edit btn-edit" title="Sửa"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm delete"
                                    onclick="event.preventDefault(); document.getElementById(\'delete-form-' . $row->id . '\').submit();">
                                    <i class="fas fa-trash btn-delete" title="Xóa"></i>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('hosting.delete', $row->id) . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '

                                </form>
                            </div>';
                })->rawColumns(['action'])
                ->make(true);
        }
        $page = 'Hosting';
        return view('backend.hosting.index', compact('title', 'page'));
    }

    public function edit($id){
        $hosting = Hosting::findOrFail($id);
        return view('backend.hosting.detail', compact('hosting'));
    }
    public function create(){
        return view('backend.hosting.detail');
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
