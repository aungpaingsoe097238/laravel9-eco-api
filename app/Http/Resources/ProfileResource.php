<?php

namespace App\Http\Resources;

use App\Http\Resources\CityResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\StateResource;
use App\Http\Resources\CountryResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function Photo($profile_image){
        if( $profile_image === 'profile.png' ){
            return asset(Storage::url('defaultProfile/'.$this->profile_image));
        }else{
            return asset(Storage::url('profile/'.$this->profile_image));
        }
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
            'profile_image' => $this->Photo($this->profile_image),  
            'address' => $this->address,
            'user' => new UserResource($this->user),
            'state' => new StateResource($this->state),
            'country' => new CountryResource($this->country),
            'city' => new CityResource($this->city),
        ];
    }
}
