<?php 

namespace App\Services;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserService{

    public function register ($user){
        $user->roles()->attach(['2']);
        $user->profile()->create(['user_id' => $user->id]);
    }

    public function assignRole($user,$role_id){
        $user->roles()->detach();
        $user->roles()->attach($role_id);
    }

}

