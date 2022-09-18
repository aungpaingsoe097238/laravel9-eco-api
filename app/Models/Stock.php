<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $with = ['photos'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function photos(){
        return $this->belongsToMany(Photo::class,'photos_stocks');
    }

}
