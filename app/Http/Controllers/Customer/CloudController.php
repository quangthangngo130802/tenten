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
        $title = "Cloud";
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
                                <a href="' . route('cloud.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
                                    Mua ngay
                                </a>

                            </div>';
                })->rawColumns(['action'])
                ->make(true);
        }
        $page = 'Cloud';
        return view('customer.cloud.index', compact('title', 'page'));
    }
}
