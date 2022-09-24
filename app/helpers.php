<?php

function json($data,$message,$code){
    return response()->json([
        'data' => $data,
        'message' => $message,
    ],$code);
}

function notFound(){
    return response()->json(
        [
            'data' => [],
            'message' => 'Data Not Found',
        ],
        404);
}
