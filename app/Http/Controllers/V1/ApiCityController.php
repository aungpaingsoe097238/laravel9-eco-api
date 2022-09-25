<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\StoreCityResource;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Requests\UpdateCityResource;
use App\Models\City;
use App\Models\State;
use DB;
use Illuminate\Http\Request;

class ApiCityController extends Controller
{
    public $with;
    public function __construct()
    {
        $this->with = ['customers'];
        $this->middleware('isAdmin',['only' => ['store','update','destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::with($this->with)->get();
        return json($cities,'success',200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $city = new City();
        $city->name = $request->name;
        $city->save();
        return json($city,'success',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $city = City::with($this->with)->find($id);
        if(!$city){
            return notFound();
        }
        return json($city,'success',200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, $id)
    {
        $city = City::find($id);
        if(!$city){
            return notFound();
        }
        $city->name = $request->name;
        $city->update();
        return json($city,'success',200);
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
            return notFound();
        }
        $city->delete();
        return json($city,'success',200);
    }
}
