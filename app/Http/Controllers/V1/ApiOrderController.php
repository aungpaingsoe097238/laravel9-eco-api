<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\OrderResource;
use Auth;
use App\Models\Card;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;

class ApiOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {

        $card = Card::all();
        $order = new Order();
        $order->user_id = Auth()->id();
        $order->status_id = $request->status_id;
        $order->payment_id = $request->payment_id;
        $order->address = $request->address;
        $order->price = 200;
        $order->save();

        // Card ထဲမှာ data များကို order_items ထဲထည့်
        $card_items = $card->where('user_id',Auth()->id())->pluck('id');
        if($card_items !== null){
            $order->cards()->sync($card_items);
        }

        return $order;

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
        //
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
        //
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
