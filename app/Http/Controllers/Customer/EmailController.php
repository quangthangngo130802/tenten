<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EmailController extends Controller
{
    public function index(Request $request, $email_type = 1)
    {
        $title = "Danh sách Email";
        if ($request->ajax()) {
            $data = Email::where('email_type',$email_type)->select('*');
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '<div style="display: flex;">
                    <a  data-id = '.$row->id.' data-type = "hosting" class="btn btn-primary btn-sm edit buy-now-btn">
                        Mua ngay
                    </a>
                </div>';
                })->rawColumns(['action'])
                ->make(true);
        }
        $page = 'Email';
        return view('customer.email.index', compact('title', 'page'));
    }
}
