<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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
                ->editColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->addColumn('action', function ($row) {
                    return '<div style="display: flex;">
                                <a href="' . route('hosting.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
                                    Mua ngay
                                </a>

                            </div>';
                })->rawColumns(['action'])
                ->make(true);
        }
        $page = 'Hosting';
        return view('customer.hosting.index', compact('title', 'page'));
    }
}
