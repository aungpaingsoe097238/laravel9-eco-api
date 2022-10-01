<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;

class ApiPaymentController extends Controller
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
        $payments = Payment::all();
        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request)
    {
        $payment = new Payment();
        $payment->name = $request->name;
        $payment->save();
        return response()->json($payment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::find($id);
        if(!$payment){
            return response()->json([
               'data' => [],
               'message' => 'Payment not found'
            ],404);
        }
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, $id)
    {
        $payment = Payment::find($id);
        if(!$payment){
            return response()->json([
                'data' => [],
                'message' => 'Payment not found'
            ],404);
        }
        $payment->name = $request->name;
        $payment->update();
        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);
        if(!$payment){
            return response()->json([
                'data' => [],
                'message' => 'Payment not found'
            ],404);
        }
        $payment->delete();
        return response()->json($payment);
    }
}
