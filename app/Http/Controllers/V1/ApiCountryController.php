<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class ApiCountryController extends Controller
{

    public function __construct()
    {
        $this->middleware('isAdmin',['only' => ['store','update','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        return json($countries,'success',200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $request)
    {
        $country = new Country();
        $country->name = $request->name;
        $country->save();
        return json($country,'success',200);
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
            return notFound();
        }
        return json($country,'success',200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCountryRequest $request, $id)
    {
        $country = Country::find($id);
        if(!$country){
            return notFound();
        }
        $country->name = $request->name;
        $country->update();
        return json($country,'success',200);
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
            return notFound();
        }
        $country->delete();
        return json($country,'success',200);
    }
}
