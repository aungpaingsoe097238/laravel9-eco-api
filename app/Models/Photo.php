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

    public function savePhotos($photos , $stock){
        foreach ($photos as $photo) {
            $newName = uniqid() . '_photo.' . $photo->extension();
            $photo->storeAs('public/photos', $newName);
            $img = Image::make($photo);
            $img->fit('500','500');
            $img->save('storage/thumbnail/' . $newName);
            // save to Database
            $photo = new Photo();
            $photo->name = $newName;
            $photo->save();
            $stock->photos()->attach($photo->id);
        }
    }

}
