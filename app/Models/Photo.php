<?php

namespace App\Models;

use App\Models\Stock;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photo extends Model
{
    use HasFactory;

    public function stocks(){
        return $this->belongsToMany(Stock::class,'photos_stocks');
    }

}
