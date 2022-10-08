<?php

namespace App\Http\Controllers\V1;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProfileResource;
use App\Http\Requests\UpdateProfileRequest;

class ApiProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin',['only' => ['destroy','store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProfileResource::collection(Profile::latest('id')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profile = Profile::find($id)->first();
        if(!$profile){
            return response()->json([],404);
        }
        return new ProfileResource($profile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request, $id)
    {
        $profile = Profile::find($id);
        if(!$profile){
            return response()->json([],404);
        }

        $profile->user_id = Auth()->id();
        $profile->state_id = $request->state_id;
        $profile->country_id = $request->country_id;
        $profile->city_id = $request->city_id;
        $profile->address = $request->address;

        if($request->hasFile('profile_image')){
            $newName = uniqid().'_profile_image.'.$request->file('profile_image')->extension();
            $request->file('profile_image')->storeAs('public/profile',$newName);
            $profile->profile_image = $newName;
        }

        $profile->update();
        return new ProfileResource($profile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
