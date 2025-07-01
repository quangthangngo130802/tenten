<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FasthotelApiController extends Controller
{
    //

    public function index(){
        $user = Auth::user();
        $service = Service::where('email', $user->email)->where('type','hotel')->first();
        return view('customer.api.index', compact('service'));
    }

    public function regenerateToken(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $newToken = Str::random(60);
        $service->token = $newToken;
        $service->save();

        return response()->json([
            'status' => true,
            'token' => $newToken
        ]);
    }
}
