<?php

namespace App\Http\Controllers\V1;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStatusRequest;
use App\Http\Requests\UpdateStatusRequest;

class ApiStatusController extends Controller
{

    public function __construct()
    {
        return $this->middleware('isAdmin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Status::all();
        return $statuses;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStatusRequest $request)
    {
        $status = new Status();
        $status->name = $request->name;
        $status->save();
        return $status;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status = Status::find($id);
        if(!$status){
            return response()->json([
            'data'    => [],
            'message' => 'Status not found']
            ,404);
        }

        return $status;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStatusRequest $request, $id)
    {
        $status = Status::find($id);

        if(!$status){
            return response()->json([
            'data'    => [],
            'message' => 'Status not found']
            ,404);
        }

        $status->name = $request->name;
        $status->update();
        return $status;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Status::find($id);

        if(!$status){
            return response()->json([
            'data'    => [],
            'message' => 'Status not found']
            ,404);
        }

        $status->delete();
        return $status;
    }
}
