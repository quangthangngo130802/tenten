<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index(){
        $page = "Thông tin công ty";
        $title = "Thông tin công ty";
        $company = Config::first();
        return view('backend.config.index', compact('company', 'page', 'title'));
    }
    public function store(CompanyRequest $request)
    {

        $data = $request->validated();

        $company = Config::updateOrCreate(
            ['tax_id' => $data['tax_id']],
            $data
        );

        // Trả về phản hồi
        return response()->json([
            'message' => $company->wasRecentlyCreated ? 'Thêm công ty mới thành công' : 'Cập nhật công ty thành công',
            'data' => $company
        ]);
    }
}
