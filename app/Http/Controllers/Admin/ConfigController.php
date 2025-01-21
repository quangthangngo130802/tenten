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
        // dd($request->all());
        $data = $request->validated();

       Config::updateOrCreate( ['id' => $request->input('id')],$data);

        toastr()->success('Cập nhật thành công.');
        return redirect()->back();
    }
}
