<?php

namespace App\Http\Controllers\V1;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;

class ApiCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::with('products','user')->get();
        return $cards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCardRequest $request)
    {
        $card = new Card();
        $card->quantity = $request->quantity;
        $card->user_id = auth()->id();
        $card->save();
        $card->products()->sync($request->products);
        return $card;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::find($id);
        if(!$card){
            return response()->json([
                'data' => [],
                'message' => 'Card not found'
            ],404);
        }
        return $card;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCardRequest $request, $id)
    {
        $card = Card::find($id);

        if(!$card){
            return response()->json([
                'data' => [],
                'message' => 'Card not found'
            ],404);
        }

        $card->quantity = $request->quantity;
        $card->products()->sync($request->products);
        $card->update();

        return $card;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = Card::find($id);
        if(!$card){
            return response()->json([
                'data' => [],
                'message' => 'Card not found'
            ],404);
        }
        $card->delete();
        return $card;
    }
}
