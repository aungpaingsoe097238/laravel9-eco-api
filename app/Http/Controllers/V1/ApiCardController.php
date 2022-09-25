<?php

namespace App\Http\Controllers\V1;

use App\Models\Card;
use App\Models\Stock;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;

class ApiCardController extends Controller
{
    public $with;

    public function __construct()
    {
        $this->with = ['stocks','user'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $cards =  Card::with($this->with)->get();
         return CardResource::collection($cards);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCardRequest $request)
    {
        try{
            DB::beginTransaction();

            $card = new Card();
            $card->quantity = $request->quantity;
            $card->user_id = auth()->id();
            $stock = Stock::find($request->stock_id);

            if($request->quantity > $stock->quantity){
                return json([],'There is no items for add to card',400);
            }else{
                $card->save();
                $card->stocks()->sync($request->stock_id);
                $stock->quantity = $stock->quantity - $request->quantity;
                $stock->update();
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
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
        $card = Card::with($this->with)->find($id);
        if(!$card){
            return notFound();
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
    public function update(UpdateCardRequest $request, $id)
    {
        $card = Card::with('stocks','user')->find($id);
        if(!$card){
            return notFound();
        }
        $card->quantity = $request->quantity;
        $card->update();
        $card->stocks()->sync($request->stock_id);
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
        $card = Card::find($id);
        if(!$card){
            return notFound();
        }
        $card->delete();
        return new CardResource($card);
    }
}
