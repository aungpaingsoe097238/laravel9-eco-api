<?php

namespace App\Http\Controllers\V1;

use App\Models\OrderPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderPriceResource;

class ApiOrderPriceController extends Controller
{

    public function __construct()
    {
        $this->middleware('isAdmin');;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return OrderPriceResource::collection(OrderPrice::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order_price = new OrderPrice();
        $order_price->price = $request->price;
        $order_price->state_id  = $request->state_id;

        $is_have_state = OrderPrice::find($request->state_id);
        if($is_have_state){
            $is_have_state->price = $request->price;
            $is_have_state->update();
            return new OrderPriceResource($is_have_state);
        }else{
            $order_price->save();
        }

        return new OrderPriceResource($order_price);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order_price = OrderPrice::find($id);
        if(!$order_price){
            return response()->json('Order Price not found');
        }
        return new OrderPriceResource($order_price);
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
        $order_price = OrderPrice::find($id);
        if(!$order_price){
            return response()->json('Order Price not found');
        }
        if($request->has('price')){
            $order_price->price = $request->price;
        }
        if($request->has('state_id')){
            $order_price->state_id  = $request->state_id;
        }
        $order_price->update();

        return new OrderPriceResource($order_price);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order_price = OrderPrice::find($id);
        if(!$order_price){
            return response()->json('Order Price not found');
        }
        $order_price->delete();
        return new OrderPriceResource($order_price);
    }
}
