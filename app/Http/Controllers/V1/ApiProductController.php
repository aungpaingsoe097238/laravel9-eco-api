<?php

namespace App\Http\Controllers\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Photo;
use App\Models\Product;
use App\Models\Stock;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ApiProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('isAdmin',['only' => ['store','update','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::search()
        ->latest('id')
        ->with('stocks')->paginate(10);
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        try {
            DB::beginTransaction();

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

            // Save Photo
            $photo = new Photo();
            if($request->hasFile('photo')){
                $photo->savePhotos($request->file('photo'),$stock);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id)->with('stocks')->first();

        if (!$product) {
            return response()->json([], 403);
        }

        return new ProductResource($product);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([], 403);
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->update();

        return new ProductResource($product);
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
            'message' => 'success',
        ], 200);

    }
}
