<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ApiProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('stocks')->get();

        return response()->json([
           'data' => $products,
           'message' => 'success'
        ],200);
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
            'name' => 'required|min:3|unique:products,name|string',
            'description' => 'required|min:3',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'photo' => 'required',
            'photo.*' => 'required|file|mimes:jpg,png',
        ]);

        // Create Product
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->save();

        // Create Stock
        $stock = new Stock();
        $stock->price = $request->price;
        $stock->quantity = $request->quantity;
        $stock->product_id = $product->id;
        $stock->save();

        // Create image
        if(!Storage::exists('public/thumbnail')){
            Storage::makeDirectory('public/thumbnail');
        }

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

        return response()->json([
           'data' => $product,
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
        $product = Product::find($id)->with('stocks')->get();

        if(!$product){
            return response()->json([],403);
        }

        return response()->json([
            'data' => $product,
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
        $product = Product::find($id);

        if(!$product){
            return response()->json([],403);
        }

        $request->validate([
            'name' => 'required|min:3|unique:products,name,'.$product->id.'|string',
            'description' => 'required|min:3',
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->update();

        return response()->json([
            'data' => $product,
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
        $product = Product::find($id);
        $product->delete();

        return response()->json([
            'data' => $product,
            'message' => 'success'
        ],200);

    }
}
