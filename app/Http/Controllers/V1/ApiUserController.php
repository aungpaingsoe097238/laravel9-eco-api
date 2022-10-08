<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserController extends Controller
{
    public $with;
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('isAdmin', ['only' => 'index', 'assiginRole', 'show']);
        $this->with = ['roles', 'profile'];
        $this->userService = $userService;
    }

    public function index()
    {
        return json(
            UserResource::collection(
                User::with($this->with)
                    ->search()
                    ->latest('id')
                    ->paginate(10)
            ),
            'success',
            200
        );
    }

    public function assignRole(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);
        $user = User::find($id);
        $this->userService->assignRole($user, $request->role_id);
        return json(new UserResource($user), 'success', 200);
    }

    public function show($id)
    {
        return json(new UserResource(User::find($id)->with($this->with)->first()), 'success', 200);
    }

    public function register(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
        ]);

        $this->userService->register($user);

        if (Auth::attempt($request->only(['email', 'password']))) {
            $data = auth()->user();
            $data['token'] = $user->createToken("phone")->plainTextToken;
            return new UserResource($data);
        }

        return json([], 'failed to create user', 422);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $data = Auth::user();
            $data['token'] = $data->createToken("phone")->plainTextToken;
            return new UserResource($data);
        }
        return notFound();
    }

    public function logout()
    {
        return json(Auth::user()->currentAccessToken()->delete(), 'success', 200);
    }
}
