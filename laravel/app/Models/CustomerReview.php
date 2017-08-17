<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReview extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'customer_name',
        'customer_email',
        'customer_mobile_number',
        'customer_reward',
        'customer_friend_reward',
        'giftcard_amount',
        'customer_friend_reward_amount',
        'customer_friend_reward_type',
        'customer_recommend',
        'customer_ratinng',
        'customer_testimonial',
        'customer_feedback',
        'company_id',
        'coupon_code',
        'expirable',
        'expiry_date',
        'last_reminder_sent_at',
        'referral_code',
        'shared',
        'share_method'
    ];    
    
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
    
    public function leads()
    {
        return $this->hasMany('App\Models\Lead');
    }    
    
    public function listAllByCompanyId($companyId)
    {
        return $this->where('company_id', $companyId)->orderBy('id', 'desc')->get();
    }    
    
    public function searchByCompanyIdByTerm($companyId, $searchTerm)
    {
        return $this->where('company_id', $companyId)->where('customer_name', 'like', $searchTerm . '%')->get();
    }  
    
    public function averageRatingByCompanyId($companyId)
    {
        return $this->where('company_id', $companyId)->whereNotNull('customer_rating')->avg('customer_rating');
    }
    
    public function totalSharesByCompanyId($companyId)
    {
        return $this->where('company_id', $companyId)->whereNotNull('facebook_post_id')->where('facebook_post_id', '<>', '')->count();
    }    
}
