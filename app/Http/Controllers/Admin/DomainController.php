<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DomainController extends Controller
{
    public function index(Request $request)
    {
        $page = "Tên miền";
        $title = "Danh sách đã đăng ký";
        $url = 'https://api-reseller.tenten.vn/v1/Domains/list.json';

        $page1 = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        $data = [
            "api_key" => "6dc564c5e650dedd67144761a3f2fcdb",
            "api_user" => "dnse002",
            "page" => $page1,
            "limit" => $limit,
        ];

        try {
            $client = new Client();
            $response = $client->post($url, [
                'form_params' => $data,
            ]);


            $responseBody = json_decode($response->getBody(), true);
            // dd($responseBody);
            if (isset($responseBody['error']) && !empty($responseBody['error'])) {
                throw new \Exception($responseBody['error']);
            }

            $domains = $responseBody['data'] ?? [];

            return view('backend.domain.index', [
                'domains' => $domains,
                'paginate' => $responseBody['paginate'] ?? [],
                'current_limit' => $limit,

                'title' => $title,
                'page' => $page,
            ]);
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
        $page = "Tên miền";
        $title = "Bảng giá tên kiền";
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

            if (isset($responseBody['error']) && !empty($responseBody['error'])) {
                throw new \Exception($responseBody['error']);
            }

            // Dữ liệu từ API
            $domains = $responseBody['data'] ?? [];

            // Tìm kiếm
            $search = $request->input('search');
            if ($search) {
                $domains = array_filter($domains, function ($key) use ($search) {
                    return stripos($key, $search) !== false;
                }, ARRAY_FILTER_USE_KEY);
            }

            // Phân trang
            $perPage = $request->get('limit', 10);
            $currentPage = $request->get('page', 1);
            $startingPoint = ($currentPage - 1) * $perPage;

            $currentPageItems = array_slice($domains, $startingPoint, $perPage, true);

            $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                count($domains),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('backend.domain.price', [
                'domain' => $paginatedItems,
                'page' => $page,
                'title' => $title,
                'search' => $search,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors('Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }
}
