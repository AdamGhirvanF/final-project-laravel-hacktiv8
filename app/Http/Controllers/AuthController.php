<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseHelper;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $req){
        $response = new ResponseHelper();
        $req->validate([
            'email' => 'required|email',
            'password'=> 'required|string|min:6'
        ]);

        $credential = $req->only(['email','password']);

        if(! $token = Auth::attempt($credential)){
            return $response->responseError('Login failed', 400);
        } else {
            $data['access_token'] = $token;
            $data['token_type'] = 'Bearer';
            $data['expires_in'] = Auth::factory()->getTTL(). ' Minutes';
            return $response->responseMessageData('Success login', $data);
        }
    }

    public function loginFirst(Request $req){
        $response = new ResponseHelper();

        return $response->responseError('You are unauthorized, please login first', 401);
    }

    public function register(Request $req){
        $response = new ResponseHelper();

        $req->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password'=> 'required|string|min:6',
            'address' => 'required'
        ]);
        
        $data = User::create([
            'name' => $req->name,
            'email'=> $req->email,
            'password'=> Hash::make($req->password),
            'address' => $req->address,
        ]);

        return $response->responseMessageData('Sucess register', $data);
    }
}
