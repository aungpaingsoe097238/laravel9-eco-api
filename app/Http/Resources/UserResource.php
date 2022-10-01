<?php

namespace App\Http\Resources;

use App\Http\Resources\CityResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\StateResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function Profile($profile){
        return [
            'id' => $profile->id,
            'user_id' => $profile->user_id,
            'state' => new StateResource($profile->state),
            'country' => new CountryResource($profile->country),
            'city' => new CityResource($profile->city),
            'profile_image' => $profile->profile_image,
        ];
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
            'name' => $this->name,
            'email' => $this->email,
            'register_date' => $this->register,
            'roles' => RoleResource::collection($this->roles),
            'profile' => $this->Profile($this->profile),
        ];
    }
}
