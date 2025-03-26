<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\PriceDomain;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\Facades\DataTables;

class DomainController extends Controller
{
    public function index(Request $request)
    {

        try {

            $title = "Danh sách Domain";
            if ($request->ajax()) {
                $data = Domain::select('*');
                return DataTables::of($data)
                ->editColumn('status', function ($row) {
                    if ($row->status == '1') {
                        return '<div class="status active">
                                    <span class="icon-check"></span> Hoạt động
                                </div>';
                    } else {
                        return '<div class="status paused">
                                    <span class="icon-warning"></span> Tạm dừng
                                </div>';
                    }
                })
                ->editColumn('created_date', function ($row) {
                    return Carbon::parse($row->created_date)->format('d/m/Y H:i:s');
                })
                ->editColumn('expiration_date', function ($row) {
                    return Carbon::parse($row->expiration_date)->format('d/m/Y H:i:s');
                })->rawColumns(['status'])
                ->make(true);
            }
            $page = 'Domain';
            return view('backend.domain.index', compact('title', 'page'));
        } catch (\Exception $e) {
            return back()->withErrors('Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }


    public function show($domain)
    {
        $page = "Tên miền";
        $title = "Thông tin chi tiết";
        $url = 'https://api-reseller.tenten.vn/v1/Domains/info.json';



        $data = [
            "api_key" => "6dc564c5e650dedd67144761a3f2fcdb",
            "api_user" => "dnse002",
            "domainName" => $domain
        ];

        try {

            $client = new Client();
            $response = $client->post($url, [
                'form_params' => $data,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if (isset($responseBody['error']) && !empty($responseBody['error'])) {
                throw new \Exception($responseBody['error']);
            }

            // dd($responseBody['data']);

            return view('backend.domain.show', [
                'domain' => $responseBody['data'] ?? [],
                'page' => $page,
                'title' => $title

            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi
            return back()->withErrors('Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }


    public function tableprice(Request $request)
    {
        try {

            $title = "Danh sách giá Domain";
            if ($request->ajax()) {
                $data = PriceDomain::select('*');
                return DataTables::of($data)
                ->editColumn('price', function ($row) {
                   return number_format($row->price);
                })
                ->editColumn('vat', function ($row) {
                    return number_format($row->vat);
                 })->make(true);
            }
            $page = 'Giá Domain';
            return view('backend.domain.price', compact('title', 'page'));
        } catch (\Exception $e) {
            return back()->withErrors('Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }
}
