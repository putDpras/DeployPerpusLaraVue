<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api')->only([
            'logout',
            'getUser'
        ]);
    }
    
    public function register(Request $request){
        $validator = Validator::make($request -> all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $role_user = Roles::where('name', 'user')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role_user->id
        ]);

        $dataUser = User::with(['roles'])->find($user->id);
        $token = JWTAuth::fromUser($user);
        return response()->json([
            "message" => "Register Berhasil",
            "token" => $token,
            "user" => $dataUser
        ], 200);
    }

    public function login(Request $request){
        $validator = Validator::make($request -> all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $creds = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($creds)) {
            return response()->json(
                ['message' => 'Email atau Kata Sandi Salah'], 
                401);
        }
        $user = Auth::guard('api')->user();
        $dataUser = User::with(['roles', 'profile'])->find($user->id);
        return response()->json([
            // "message" => "user berhasil login",
            "user" => $dataUser,
            "token" => $token,
            
        ], 200);
    }

    public function logout()
    {
        
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Logout Berhasil'
        ], 200);
    }
    
    public function getUser()
    {
        $user = Auth::guard('api')->user();
        if(!$user){
            return response()->json([
                "message" => "Silahkan login terlebih dahulu",
            ], 401);
        }
        return response()->json([
            "message" => "berhasil get user",
            "user" => User::with(['roles', 'profile'])->find($user->id)
        ], 200);
    }



}
