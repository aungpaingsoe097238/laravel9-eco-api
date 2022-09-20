<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\Profile;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;

class ApiUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',['only'=>'index']);
    }



    public function index(){

        $users = User::with('roles','profile')->latest('id')->paginate(10);
        return UserResource::collection($users);

    }

    public function register(CreateUserRequest $request){

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password)
        ]);

        // Create Role
        $user->roles()->attach(['2']);

        // Create Customers
        $user->profile()->save(new Profile([
            'user_id' => $user->id,
        ]));

        if(Auth::attempt($request->only(['email','password']))){
            $token = Auth::user()->createToken("phone")->plainTextToken;
            return response()->json([
                'token' => $token,
                'data' => new UserResource(User::with('roles','profile')->where('id',$user->id)->first()),
            ]);
        }

        return response()->json([],403);

    }

    public function login(UpdateUserRequest $request){
        if(Auth::attempt($request->only(['email','password']))){
            $token = Auth::user()->createToken("phone")->plainTextToken;
            return response()->json([
                'token' => $token,
                'data' => new UserResource(User::with('roles','profile')->where('id',Auth()->id())->first()),
            ]);
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
