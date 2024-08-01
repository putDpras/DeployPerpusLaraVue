<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $owner_id = Roles::where('name', 'owner')->first();
        $user = Auth::guard('api')->user();
        if($user && $user->role_id == $owner_id->id){
            return $next($request);
        }
        return response()->json([
            'message' => 'Anda tidak bisa mengakses halaman ini'
        ], 401);
    }
}
