<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stripe_subscription_id',
        'current_period_start',
        'current_period_end',
        'subscription_package_id',
        'subscription_status',
        'stripe_subscription_status',
        'trial_start',
        'trial_end',
        'user_id'
    ];    
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    } 
}
