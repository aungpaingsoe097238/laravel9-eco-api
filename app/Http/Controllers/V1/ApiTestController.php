<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\Stock;
use App\Models\Product;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Throw_;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exceptions\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // JOIN products , categories and stocks
        // $stocks = Stock::join('products', 'stocks.product_id', '=', 'products.id')
        //     ->select('stocks.id', 'stocks.price', 'stocks.quantity', 'products.name', 'products.description', 'products.category_id')
        //     ->get();

        // Search By product's name in Stock table
        // $product = Product::where('name','Dolorum atque commodi vero aut a dolores.')->first();
        // $stocks = $product->stocks()->first();

        $query = DB::table('users')->select('name');
        $users = $query->addSelect('id')->get();
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
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
