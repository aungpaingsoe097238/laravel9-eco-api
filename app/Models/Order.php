<?php

namespace App\Models;

use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class,'order_items');
    }

}
