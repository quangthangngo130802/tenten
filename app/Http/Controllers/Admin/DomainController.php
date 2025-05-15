<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\PriceDomain;
use App\Models\Service;
use App\Models\User;
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
            $users = User::where('role_id', '!=', 1)->get();
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
                    })
                    ->addColumn('another_column', function ($row) {
                        return '<button class="btn-transfer" data-id="' . $row->id . '"  data-domain="' . $row->name . '" data-toggle="modal" data-target="#transferModal" title="Chuyển dữ liệu">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>';
                    })
                    ->rawColumns(['status', 'another_column'])
                    ->make(true);
            }
            $page = 'Domain';
            return view('backend.domain.index', compact('title', 'page', 'users'));
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


    public function transfer(Request $request)
    {
        $domain = $request->domain;
        $parts = explode('.', $domain);

        $name = $parts[0]; // "thangqt"
        $extension = '.' . $parts[1];

        $domain = Domain::where('name', $request->domain)->first();
        // dd($domain);
        $created = Carbon::parse($domain->created_date)->startOfDay();
        $expired = Carbon::parse($domain->expiration_date)->startOfDay();
        $months = $created->diffInMonths($expired);

        $service = Service::where('type', 'domain')->where('domain', $name)->where('domain_extension', $extension)->first();
        if (!$service) {
            Service::create([
                'email' => $request->username,
                'type' => 'domain',
                'domain' =>  $name,
                'domain_extension' => $extension,
                'number' => $months,
                'status' =>  $domain->status == 1 ? 'active' : 'unactive',
                'price' => 0,
                'active_at' => $domain->created_date
            ]);
        }else{
            $service->update([
                'email' => $request->username,
                'active_at' => $domain->created_date
            ]);
        }

        return response()->json(['message' => 'Chuyển domain thành công!']);
    }

    public function checkdomain(){
        $listDomain = $this->domainPrice();
        return view('check.index', compact('listDomain'));
    }

    public function submitcheckdomain(Request $request){
        $domain = $request->input('domain');

        if (!$domain) {
            return response()->json(['error' => 'Domain is required'], 400);
        }

        // dd($request->toArray());
        $client = new Client();

        try {
            $response = $client->post('https://api-reseller.tenten.vn/v1/Domains/search.json', [
                'json' => [
                    'api_key' => '6dc564c5e650dedd67144761a3f2fcdb',
                    'api_user' => 'dnse002',
                    'domainName' => $domain,
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            

            // Trả dữ liệu nguyên gốc hoặc xử lý tùy ý trước khi trả
            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi khi gọi API bên ngoài',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function domainPrice()
    {

        $url = 'https://api-reseller.tenten.vn/v1/Domains/price.json';

        $data = [
            "api_key" => "6dc564c5e650dedd67144761a3f2fcdb",
            "api_user" => "dnse002",
        ];

        try {
            $client = new Client();
            $response = $client->post($url, [
                'form_params' => $data,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            return $responseBody['data'];
            // dd($responseBody['data']);

        } catch (\Exception $e) {
            return back()->withErrors('Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }
}
