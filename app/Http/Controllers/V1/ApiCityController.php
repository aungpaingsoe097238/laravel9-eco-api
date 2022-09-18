<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class ApiCityController extends Controller
{

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
        $cities = City::all();
        return $this->responseType($cities,'success',200);
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

        $city = new City();
        $city->name = $request->name;
        $city->save();

        return $this->responseType($city,'success',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $city = City::find($id);

        if(!$city){
            return response()->json([],403);
        }

        return $this->responseType($city,'success',200);
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
        $city = City::find($id);

        if(!$city){
            return response()->json([],403);
        }

        $request->validate([
            'name' => 'required|unique:states,name,'.$city->id.'|min:3'
        ]);

        $city->name = $request->name;
        $city->update();

        return $this->responseType($city,'success',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::find($id);
        if(!$city){
            return response()->json([],403);
        }
        $city->delete();
        return $this->responseType($city,'Data Successfully Deleted',200);
    }
}
