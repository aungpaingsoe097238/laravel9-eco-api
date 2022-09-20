<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Models\Photo;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateStockRequest;
use App\Http\Resources\StockResource;

class ApiStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::all();
        return StockResource::collection($stocks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStockRequest $request)
    {

        try{
            DB::beginTransaction();

            $stock = new Stock();
            $stock->price = $request->price;
            $stock->quantity = $request->quantity;
            $stock->product_id = $request->product_id;
            $stock->save();

            // Save Photo
            $photo = new Photo();
            if($request->hasFile('photo')){
                $photo->savePhotos($request->file('photo'),$stock);
            }

            DB::commit();
        }catch(Exception $ex){
            DB::rollBack();
        }

        return new StockResource($stock);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stock = Stock::find($id);
        if(!$stock){
            return response()->json([],403);
        }
        return new StockResource($stock);

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
        $stock = Stock::find($id);
        if(!$stock){
            return response()->json([],403);
        }
        $stock->price = $request->price;
        $stock->quantity = $request->quantity;
        $stock->product_id = $request->product_id;
        $stock->update();

        if($request->hasFile('photo')){
            // Delete Old Data
            if($stock->photos){
                foreach ($stock->photos as $photo){
                    $stock->photos()->detach($photo->id);
                    Photo::where('id',$photo->id)->delete();
                    Storage::delete('public/storage/photos/'.$photo->name);
                    Storage::delete('public/storage/thumbnail/'.$photo->name);
                }
            }
            // Save Photo
            $photo = new Photo();
            if($request->hasFile('photo')){
                $photo->savePhotos($request->file('photo'),$stock);
            }
        }

       return new StockResource($stock);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = Stock::find($id);
        if(!$stock){
            return response()->json([],404);
        }
        $stock->delete();
        return new StockResource($stock);
    }
}
