<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Businesse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BusinessController extends Controller
{
    //
    public function index(Request $request)
    {
        $title = "Danh sách đăng ký kinh doanh";
        if ($request->ajax()) {

            $data = Businesse::select('*');
            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('businessCode', function ($row) {
                return '<a href="' . route('business.detail', $row->id) . '" class=" text-primary "> ' . $row->businessCode . '</a>';
            })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="checkbox-item"  name="selected[]" value="' . $row->id . '">';
                })
             ->rawColumns(['checkbox', 'businessCode'])
                ->make(true);
        }
        $page = "Danh sách đăng ký kinh doanh";
        return view('backend.business.index', compact('title', 'page'));
    }

    public function delete(Request $request){
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Không có mục nào được chọn']);
        }

        try {
            Businesse::whereIn('id', $ids)->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Xóa không thành công']);
        }
    }

    public function view($id){
       $business = Businesse::find($id);
       $title = "Thông tin đăng ký kinh doanh";
       $page = "Thông tin đăng ký kinh doanh";
      return view('backend.business.view', compact('business', 'title', 'page'));
    }
}
