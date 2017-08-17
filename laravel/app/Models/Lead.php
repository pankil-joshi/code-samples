<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lead_full_name',
        'lead_email',
        'lead_mobile_number',
        'company_id',
        'customer_review_id',
        'comments',
        'created_at'
    ];  
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function listAllByCompanyId($companyId)
    {
        return $this->where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();
    }
    
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
    
    public function customerReview()
    {
        return $this->belongsTo('App\Models\CustomerReview');
    }
    
    public function totalByCompanyId($companyId) {
        
        return $this->where('company_id', $companyId)->count();
    }
}
