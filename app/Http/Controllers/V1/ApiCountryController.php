<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class ApiCountryController extends Controller
{

    public function __construct()
    {
        $this->middleware('isAdmin',['only' => ['store','update','destroy']]);
    }


    public function responseType($data,$message,$code){
        return response()->json([
            'data' => $data,
            'message' => $message,
        ],$code);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        return $this->responseType($countries,'success',200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|unique:states,name|string'
        ]);
        $country = new Country();
        $country->name = $request->name;
        $country->save();
        return $this->responseType($country,'success',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country = Country::find($id);

        if(!$country){
            return response()->json([],403);
        }

        return $this->responseType($country,'success',200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $country = Country::find($id);
        if(!$country){
            return response()->json([],403);
        }
        $request->validate([
            'name' => 'required|unique:states,name,'.$country->id.'|min:3'
        ]);
        $country->name = $request->name;
        $country->update();
        return $this->responseType($country,'success',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::find($id);
        if(!$country){
            return response()->json([],403);
        }
        $country->delete();
        return $this->responseType($country,'Data Successfully Deleted',200);
    }
}
