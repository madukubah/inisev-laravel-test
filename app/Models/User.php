<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];

    public function subscriptions(){
        return $this->hasMany('App\Models\UserSubscription');
    }
}
