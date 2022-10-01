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
    public $with;

    public function __construct()
    {
        $this->with = ['stocks','category','orders'];
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
        ->with($this->with)
        ->latest('id')
        ->paginate(10);
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
            $product->category_id = $request->category_id;
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
    public function show(Product $product)
    {
        if (!$product) {
            return notFound();
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
            return notFound();
        }
        $product->name = $request->name;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
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
        if(!$product){
            return notfound();
        }
        $product->delete();
        return new ProductResource($product);
    }
}
