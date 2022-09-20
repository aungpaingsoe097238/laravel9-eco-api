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
            DB::transaction();

            $stock = new Stock();
            $stock->price = $request->price;
            $stock->quantity = $request->quantity;
            $stock->product_id = $request->product_id;
            $stock->save();

            if($request->hasFile('photo')){
                foreach($request->file('photo') as $photo){
                    $newName = uniqid().'_photo.'.$photo->extension();
                    $photo->storeAs('public/photos',$newName);
                    $img = Image::make($photo);
                    $img->fit('500','500');
                    $img->save('storage/thumbnail/'.$newName);
                    // save to Database
                    $photo = new Photo();
                    $photo->name = $newName;
                    $photo->save();
                    $stock->photos()->attach($photo->id);
                }
            }

            DB::commit();
        }catch(Exception $ex){
            DB::rollBack();
        }

        return response()->json([
           'data' => $stock,
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
        $stock = Stock::find($id);

        if(!$stock){
            return response()->json([],403);
        }

        return response()->json([
            'data' => $stock,
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

        // Create image
        if(!Storage::exists('public/thumbnail')){
            Storage::makeDirectory('public/thumbnail');
        }

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

            foreach($request->file('photo') as $photo){
                $newName = uniqid().'_photo.'.$photo->extension();
                $photo->storeAs('public/photos',$newName);
                $img = Image::make($photo);
                $img->fit('500','500');
                $img->save('storage/thumbnail/'.$newName);

                // save to Database
                $photo = new Photo();
                $photo->name = $newName;
                $photo->save();

                $stock->photos()->attach($photo->id);

            }
        }

        return response()->json([
            'data' => $stock,
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
        $stock = Stock::find($id);
        if(!$stock){
            return response()->json([],404);
        }
        $stock->delete();
        return response()->json([],403);
    }
}
