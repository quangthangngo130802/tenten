<?php

namespace App\Http\Middleware;

use App\Models\Service;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Missing or invalid Authorization header'], 401);
        }

        $token = substr($authHeader, 7);

        $client = Service::where('token', $token)->first();

        if (!$client) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $request->merge(['api_client' => $client]);

        return $next($request);
    }
}
