<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_name', 'business_address', 'business_industry'
    ];
    
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\CustomerReview');
    }
    
    public function leads()
    {
        return $this->hasMany('App\Models\Lead');
    }  
    
    public function settings()
    {
        return $this->hasOne('App\Models\CompanySetting');
    }     
}
