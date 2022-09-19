<?php

namespace App\Http\Resources;

use App\Http\Resources\PhotoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function StockStatus($qty){
        $status = '';
        if($qty > 10){
            $status = 'avaliable';
        }else if($qty > 0){
            $status = 'few';
        }else if($qty === 0){
            $status = 'out of stock';
        }
        return $status;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'stock_status' => $this->StockStatus($this->quantity),
            'product_id' => $this->product_id,
            'product' => $this->product,
            'photos' => PhotoResource::collection($this->photos),
        ];
    }
}
