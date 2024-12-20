<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CloudRequest;
use App\Http\Requests\HostingRequest;
use App\Models\Cloud;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CloudController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Hosting";
        if ($request->ajax()) {
            $data = Cloud::select('*');
            return DataTables::of($data)

                ->addColumn('action', function ($row) {
                    return '<div style="display: flex;">
                                <a href="' . route('cloud.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
                                    <i class="fas fa-edit btn-edit" title="Sửa"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm delete"
                                    onclick="event.preventDefault(); document.getElementById(\'delete-form-' . $row->id . '\').submit();">
                                    <i class="fas fa-trash btn-delete" title="Xóa"></i>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('cloud.delete', $row->id) . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '

                                </form>
                            </div>';
                })->rawColumns(['action'])
                ->make(true);
        }
        $page = 'Hosting';
        return view('backend.cloud.index', compact('title', 'page'));
    }

    public function edit($id){
        $cloud = Cloud::findOrFail($id);
        return view('backend.cloud.detail', compact('cloud'));
    }
    public function create(){
        return view('backend.cloud.detail');
    }

    public function update(CloudRequest $request, $id)
    {
        $user = Cloud::find($id);
        $credentials = $request->validated();

        $user->update($credentials);
        toastr()->success('Cập nhật thành công.');
        return redirect()->route('cloud.index');
    }

    public function store(CloudRequest $request)
    {
        $credentials = $request->validated();
        Cloud::create($credentials);
        toastr()->success('Thêm thành công.');
        return redirect()->route('cloud.index');
    }
}
