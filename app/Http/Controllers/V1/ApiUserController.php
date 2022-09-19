<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserController extends Controller
{
    public function register(CreateUserRequest $request){

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password)
        ]);

        // Create Role
        $user->roles()->attach(['2']);

        // Create Customers
        $user->customer()->save(new Customer([
            'user_id' => $user->id,
        ]));

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

    public function login(UpdateUserRequest $request){
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
        return response()->json([
            'data' => 'User Logout Successfully',
            'message' => 'success'
        ],200);
    }
}
