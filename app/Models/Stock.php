<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'price' ,
        'quantity',
        'product_id',
    ];
    
    protected $with = ['photos','product'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function photos(){
        return $this->belongsToMany(Photo::class,'photos_stocks');
    }

}
