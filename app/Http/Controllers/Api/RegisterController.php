<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required',
            'email'    => 'required',
            'domain'   => 'required',
        ]);

        $user = Service::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'type'     =>  'crm',
            'domain'   =>  $data['subdomain'],
            'domain_extension' => 'crm360.dev',
            'price' => 0,
        ]);

        return response()->json([
            'user'    => $user,
        ]);
    }
}
