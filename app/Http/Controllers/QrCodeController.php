<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class QrCodeController extends Controller
{
    public function index(Request $request)
    {
        $title = "Danh sách QR Code";
        $user = Auth::user();
        if ($request->ajax()) {
            $data = QrCode::select('*');
            if ($user->role_id != 1) {
                $data = $data->where('user_id', $user->id);
            }
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '<div style="display: flex;">
                                <a href="' . route('qrcode.edit', $row->id) . '" class="btn btn-primary btn-sm edit">
                                    <i class="fas fa-edit btn-edit" title="Sửa"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm delete"
                                onclick="confirmDelete(event, ' . $row->id . ')">
                                    <i class="fas fa-trash btn-delete" title="Xóa"></i>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('qrcode.delete', $row->id) . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '
                                </form>
                            </div>';
                })->addColumn('image', function ($row) {
                    return '<div>
                                <img src="' . $row->default_link . '" alt="QR Code" style="max-width: 50px; height: auto;">
                            </div>';
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        $page = 'QR Code';
        return view('qr_code.index', compact('title', 'page'));
    }
    public function create()
    {
        return view('qr_code.createqr');
    }

    public function edit($id)
    {
        $qrCode = QrCode::find($id);

        return view('qr_code.createqr', compact('qrCode'));
    }
    public function imageurl(Request $request)
    {
        Log::info($request->all());

        $encodedLink = base64_encode($request->qr_link);

        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={$encodedLink}";

        return response()->json([
            'success' => true,
            'message' => 'QR Code created successfully!',
            'qr_code_url' => $qrCodeUrl
        ]);
    }
    public function save(Request $request, $id = null)
    {
        $encodedLink = base64_encode($request->qr_link);
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={$encodedLink}";
        $qrcode = null;
        $user = Auth::user();
        if ($id) {
            $qrcode = QrCode::find($id);
            $qrcode->qr_name = $request->qr_name;
            $qrcode->qr_link = $request->qr_link;
            $qrcode->default_link = $qrCodeUrl;
            $qrcode->save();
        } else {
            $qrcode = new QrCode();
            $qrcode->qr_name = $request->qr_name;
            $qrcode->qr_link = $request->qr_link;
            $qrcode->default_link = $qrCodeUrl;
            $qrcode->user_id = $user->id;
            $qrcode->save();
        }
        return redirect()->route('qrcode.edit', ['id' => $qrcode->id])->with('success', 'QR Code has been saved!');
    }

    public function delete($id)
    {
        $cloud = QrCode::find($id);
        $cloud->delete();
        toastr()->success('Xóa thành công công.');
        return redirect()->route('qrcode.index');
    }
}
