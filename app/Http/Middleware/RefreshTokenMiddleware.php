<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     // try {
    //     //     return $next($request);
    //     // } catch (AuthenticationException $e) {
    //     //     // Jika token akses kadaluwarsa, coba refresh token
    //     //     try {
    //     //         $user = $request->user();
    //     //         $user->tokens()->delete();
    //     //         Auth::user()->token()->refresh();
    //     //         return $next($request);
    //     //     } catch (\Exception $e) {
    //     //         // Jika refresh token juga kadaluwarsa atau tidak valid, arahkan ke proses autentikasi
    //     //         return response()->json(['message' => 'Unauthorized'], 401);
    //     //     }
    //     // }
    // }
}
