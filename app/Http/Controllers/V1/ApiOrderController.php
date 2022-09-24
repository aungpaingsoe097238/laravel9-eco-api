<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\OrderResource;
use Auth;
use App\Models\Card;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Support\Facades\DB;

class ApiOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin', ['only' => ['update','destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::latest('id','desc')->paginate(10);
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $order = new Order();
        $order->user_id = Auth()->id();
        $order->status_id = 1;
        $order->payment_id = $request->payment_id;
        $order->address = $request->address;
        $order->state_id = $request->state_id;

        // State Price
        $price = DB::table('order_prices')->where('state_id',$request->state_id);
        if(!$price->count() > 0){
            $order->price = 100;
        }

        $order->price = $price->first()->price;

        // Card ထဲမှာ data များကို order_items ထဲထည့်
        $card_items = DB::table('cards')->where('user_id',Auth()->id());
        if(!$card_items->count() > 0){
            return response()->json('There is no card data for order');
        }

        $order->save();
        $order->products()->sync($card_items->pluck('id'));
        $card_items->delete();

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        if(!$order){
            return response()->json('Order not found');
        }
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        $order = Order::find($id);

        if(!$order){
            return response()->json('Order not found');
        }

        $order->user_id = $request->user_id;

        if($request->has('status_id')){
            $order->status_id = $request->status_id;
        }

        if($request->has('state_id')){
            $order->state_id = $request->state_id;
            $price = DB::table('order_prices')->where('state_id',$request->state_id);
            if(!$price->count() > 0){
                $order->price = 100;
            }
            $order->price = $price->first()->price;
        }

        if($request->has('payment_id')){
            $order->payment_id = $request->payment_id;
        }

        if($request->has('address')){
            $order->address = $request->address;
        }

        $order->save();

        if($request->has('products')){
            $order->products()->sync($request->products);
        }

        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if(!$order){
            return response()->json('Order not found');
        }

        $order->delete();
        return new OrderResource($order);
    }
}
