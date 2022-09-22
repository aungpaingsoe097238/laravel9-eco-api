<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function stocks(){
        return $this->hasMany(Stock::class);
    }

    public function scopeSearch($quary){
        return $quary->when(isset(request()->keyword),function ($q) {
            $keyword = request()->keyword;
            $q->where('name', "LIKE", "%$keyword%")
                ->orWhere('description', "LIKE", "%$keyword%");
        });
    }

}
