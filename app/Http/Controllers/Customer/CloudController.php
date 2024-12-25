<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cloud;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CloudController extends Controller
{
    public function index(Request $request, $type_id = 1)
    {
        $title = "Danh sách Cloud";
        if ($request->ajax()) {
            $data = Cloud::where('type_id',$type_id)->select('*');
            return DataTables::of($data)
            ->editColumn('price', function ($row) {
                return number_format($row->price);
            })
            ->editColumn('total_cost', function ($row) {
                return number_format($row->total_cost);
            })
                ->addColumn('action', function ($row) {
                    return '<div style="display: flex;">
                                <a data-id = '.$row->id.' data-type = "cloud"  class="btn btn-primary btn-sm edit buy-now-btn">
                                    Mua ngay
                                </a>

                            </div>';
                })->rawColumns(['action'])
                ->make(true);
        }
        $page = 'Cloud Server';
        return view('customer.cloud.index', compact('title', 'page'));
    }
}
