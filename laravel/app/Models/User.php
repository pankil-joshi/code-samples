<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'mobile_number', 'email', 'password', 'company_id', 'email_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'stripe_customer_id'
    ];
    
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
    
    public function stripeCards()
    {
        return $this->hasMany('App\Models\UserStripeCards');
    }
    
    public function subscription()
    {
        return $this->hasOne('App\Models\UserSubscription');
    }    
    
    public function verified()
    {
        $this->verified = '1';
        $this->email_token = null;
        $this->save();
        
        return $this;
    }    
}
