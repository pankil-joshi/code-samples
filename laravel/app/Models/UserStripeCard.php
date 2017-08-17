<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStripeCard extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_name', 
        'business_address', 
        'stripe_card_id', 
        'last4', 
        'brand',
        'country',
        'address_city',
        'address_country',
        'address_line1',
        'address_line2',
        'address_state',
        'address_zip',
        'user_id',
        'exp_month',
        'exp_year',
        'fingerprint',
        'name',
        'is_default'
    ];
    
    protected $hidden = [
        'stripe_card_id', 'fingerprint',
    ];    
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    } 
}
