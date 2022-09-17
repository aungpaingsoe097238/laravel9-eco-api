<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password)
        ]);
        if(Auth::attempt($request->only(['email','password']))){
            $token = Auth::user()->createToken("phone")->plainTextToken;
            return response()->json([
                'message' => 'User Create Successfully',
                'data' => $user,
                'token' => $token,
            ],200);
        }
        return response()->json([],403);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if(Auth::attempt($request->only(['email','password']))){
            $token = Auth::user()->createToken("phone")->plainTextToken;
            return response()->json([
                'message' => 'User Login Successfully',
                'data' => Auth::user(),
                'token' => $token,
            ],200);
        }
        return response()->json(['User not found.'],403);
    }

    public function logout(){
        Auth::user()->currentAccessToken()->delete();
        return response()->json([],204);
    }
}
