<?php

namespace App\Http\Controllers\V1;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use Illuminate\Support\Facades\Auth;

class ApiCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::with('user','products')->get();
        return $cards;
        return CardResource::collection($cards);
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
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|min:1|integer',
        ]);

        $card = new Card();
        $card->product_id = $request->product_id;
        $card->user_id    = Auth()->id();
        $card->quantity = $request->quantity;
        $card->save();
        return new CardResource($card);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::with('user','products')->find($id);

        if(!$card){
            return response()->json([
                'data' => [],
                'message' => 'Data not found.'
            ],404);
        }

        return new CardResource($card);
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

        $card = Card::with('user','products')->find($id);

        if(!$card){
            return response()->json([
                'data' => [],
                'message' => 'Data not found.'
            ],404);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id,numeric',
            'quantity'   => 'required|numeric',
        ]);

        $card->product_id = $request->product_id;
        $card->user_id    = Auth()->id();
        $card->quantity = $request->quantity;
        $card->update();

        return new CardResource($card);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = Card::with('user','products')->find($id);

        if(!$card){
            return response()->json([
                'data' => [],
                'message' => 'Data not found.'
            ],404);
        }

        $card->delete();
        return new CardResource($card);
    }
}
