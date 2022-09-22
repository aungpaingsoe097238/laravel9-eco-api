<?php

namespace App\Models;

use App\Models\Stock;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function stocks(){
        return $this->hasMany(Stock::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function scopeSearch($quary){
        return $quary->when(isset(request()->keyword),function ($q) {
            $keyword = request()->keyword;
            $q->where('name', "LIKE", "%$keyword%")
                ->orWhere('description', "LIKE", "%$keyword%");
        });
    }

}
