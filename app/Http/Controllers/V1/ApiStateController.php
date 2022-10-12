<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\UpdateStateRequest;
use App\Http\Resources\StateResoruce;
use App\Models\State;
use Illuminate\Http\Request;

class ApiStateController extends Controller
{

    private $with ;

    public function __construct()
    {
        $this->middleware('isAdmin',['only' => ['store','update','destroy']]);
        $this->with = ['order_price'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return json( StateResoruce::collection(State::with($this->with)->get()) ,'success',200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStateRequest $request)
    {
        return json(new StateResoruce(State::create($request->validated())) ,'success',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $state = State::find($id);
        if(!$state){
            return notFound();
        }
        return json($state,'success',200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStateRequest $request, $id)
    {
        $state = State::find($id);
        if(!$state){
            return notFound();
        }
        $state->update($request->validated());
        return json($state,'success',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $state = State::find($id);
        if(!$state){
           return notFound();
        }
        $state->delete();
        return json($state,'success',200);
    }
}
