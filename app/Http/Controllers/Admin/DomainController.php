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
        $url = 'https://api.thedogapi.com/v1/breeds';

        try {
            // Gửi yêu cầu GET tới API
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url);

            // Giải mã phản hồi từ JSON
            $responseBody = json_decode($response->getBody(), true);

            // Kiểm tra và debug dữ liệu phản hồi
            if (empty($responseBody)) {
                throw new \Exception("Phản hồi API rỗng.");
            }

            // Hiển thị dữ liệu để kiểm tra (hoặc xử lý tùy ý)
            dd($responseBody); // Debug toàn bộ dữ liệu phản hồi

            // Trả về view (sau khi kiểm tra dữ liệu phản hồi)
            return view('backend.domain.show', [
                'domain' => $responseBody,
                'page' => $page,
                'title' => $title,
            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi
            return back()->withErrors('Không thể tải dữ liệu: ' . $e->getMessage());
        }
    }

}
