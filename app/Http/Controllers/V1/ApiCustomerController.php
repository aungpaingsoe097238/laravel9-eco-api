<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();

        return response()->json([
            'data' => $customers,
            'message' => 'success'
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomerRequest $request)
    {
        return $request;
        $customer = new Customer();
        $customer->user_id = Auth::id();
        $customer->state_id = $request->state_id;
        $customer->country_id = $request->country_id;
        $customer->city_id = $request->city_id;
        $customer->address = $request->address;

        if($request->hasFile($request->profile_image)){
            $customer->profile_image = $request->profile_image;
        }

        $customer->save();

        return response()->json([
            'data' => $customer,
            'message' => 'success'
        ],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id)->with('user','state','country','city')->get();
        if(!$customer){
            return response()->json([],403);
        }
        return response()->json([
            'data' => $customer,
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
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::find($id);

        if(!$customer){
            return response()->json([],403);
        }

        $customer->user_id = Auth::id();
        $customer->state_id = $request->state_id;
        $customer->country_id = $request->country_id;
        $customer->city_id = $request->city_id;
        $customer->address = $request->address;

        if($request->hasFile($request->profile_image)){
            $customer->profile_image = $request->profile_image;
        }

        $customer->update();

        return response()->json([
            'data' => $customer,
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
        $customer = Customer::find($id);
        if(!$customer){
            return response()->json([],403);
        }
        $customer->delete();
        return response()->json([
            'data' => $customer,
            'message' => 'success'
        ],200);
    }
}
