<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class ApiStateController extends Controller
{

    public function __construct()
    {
        $this->middleware('isAdmin',['only' => ['store','update','destroy']]);
    }

    public function responseType($data,$message,$code){
        return response()->json([
            'data' => $data,
            'message' => $message,
        ],$code);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::all();
        return $this->responseType($states,'success',200);

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
            'name' => 'required|min:3|unique:states,name|string'
        ]);

        $state = new State();
        $state->name = $request->name;
        $state->save();

        return $this->responseType($state,'success',200);

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
            return response()->json([],403);
        }

        return $this->responseType($state,'success',200);

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
        $state = State::find($id);

        if(!$state){
            return response()->json([],403);
        }

        $request->validate([
            'name' => 'required|unique:states,name,'.$state->id.'|min:3'
        ]);

        $state->name = $request->name;
        $state->update();

        return $this->responseType($state,'success',200);
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
        $state->delete();

        return $this->responseType($state,'Data Successfully Deleted',200);
    }
}
