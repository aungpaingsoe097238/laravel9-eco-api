<?php

namespace App\Http\Controllers\V1;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;

class ApiProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin',['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::latest('id')->paginate(10);

        return response()->json([
            'data' => $profiles,
            'message' => 'success'
        ],200);
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
        $profile = Profile::find($id)->get();
        if(!$profile){
            return response()->json([],403);
        }
        return response()->json([
            'data' => $profile,
            'message' => 'success'
        ],200);
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

        return response()->json([
            'data' => $profile,
            'message' => 'success'
        ],200);
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
