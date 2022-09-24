<?php

namespace App\Models;

use App\Models\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderPrice extends Model
{
    use HasFactory;

    public function state(){
        return $this->belongsTo(State::class);
    }

}
