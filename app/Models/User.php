<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Profile;
use App\Models\Customer;
use Attribute;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $appends = ['register'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class,'roles_users');
    }

    public function isAdmin(){
        foreach (Auth::user()->roles as $role){
            return $role->name === 'admin';
        }
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function scopeSearch($query){
        return $query->when(request('keyword'),function($q){
            $q  ->where('name', 'like', '%'. request('keyword'). '%')
                ->orWhere('email', 'like', '%'. request('keyword').'%');
        });
    }

    public function getRegisterAttribute(){
        return $this->created_at->diffForHumans();
    }


}
