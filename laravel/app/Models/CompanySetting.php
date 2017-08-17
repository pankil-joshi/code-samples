<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'customer_reward',
        'customer_friend_reward',
        'giftcard_amount',
        'customer_friend_reward_amount',
        'customer_friend_reward_type',
        'expirable',
        'expiry_date'
    ];    
    
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
    
    public function get($companyId)
    {
        return $this->where('company_id', $companyId)->first();
    }       
}
