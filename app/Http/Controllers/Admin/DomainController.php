<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

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

            if (isset($responseBody['error']) && !empty($responseBody['error'])) {
                throw new \Exception($responseBody['error']);
            }

            return view('backend.domain.index', [
                'domains' => $responseBody['data'] ?? [],
                'paginate' => $responseBody['paginate'] ?? [],
                'current_limit' => $limit,
                'title' => $title,
                'page' => $page,
            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi
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

    public function tableprice()
    {
        $page = "Tên miền";
        $title = "Bảng giá";
        $url = 'https://api-reseller.tenten.vn/v1/Domains/price.json';

        $data = [
            [
                'name' => 'api_key',
                'contents' => '6dc564c5e650dedd67144761a3f2fcdb',
            ],
            [
                'name' => 'api_user',
                'contents' => 'dnse002',
            ],
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->post($url, [
            'multipart' => $data,
        ]);

        $responseBody = json_decode($response->getBody(), true);
        dd($responseBody);
        // $data = [
        //     "api_key" => "6dc564c5e650dedd67144761a3f2fcdb",
        //     "api_user" => "dnse002",
        // ];

        // try {

            // $client = new \GuzzleHttp\Client();
            // $response = $client->post($url, [
            //     'form_params' => $data,
            // ]);

            // $responseBody = json_decode($response->getBody(), true);
            // // dd($responseBody);
            // if (isset($responseBody['error']) && !empty($responseBody['error'])) {
            //     throw new \Exception($responseBody['error']);
            // }

            // dd($responseBody['data']);

            // return view('backend.domain.show', [
            //     'domain' => $responseBody['data'] ?? [],
            //     'page' => $page,
            //     'title' => $title

            // ]);
        // } catch (\Exception $e) {
        //     // Xử lý lỗi
        //     return back()->withErrors('Không thể tải dữ liệu: ' . $e->getMessage());
        // }
    }
}
