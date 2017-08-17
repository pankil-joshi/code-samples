<?php


namespace App\Http\Controllers\Account;

use App\Models\CustomerReview;
use App\Models\Lead;
use App\Models\CompanySetting;

class DashboardController {
    
    public function requestReferral() {
        $user = \Auth::user();
        $companySetting = new CompanySetting();       
        $settings = $companySetting->get($user->company_id);
        
        return view('account.request-referral')->with(
            [
                'meta' => ['title' => 'Request Referral'],
                'settings' => $settings
            ]);        
    }
    
    public function performance() {
        $user = \Auth::user();
        $customerReview = new CustomerReview();
        $lead = new Lead();
        $companySetting = new CompanySetting();       
        $averageRating = $customerReview->averageRatingByCompanyId($user->company_id);
        $totalShares = $customerReview->totalSharesByCompanyId($user->company_id);
        $totalLeads = $lead->totalByCompanyId($user->company_id);
        
        return view('account.performance')->with(
            [
                'meta' => ['title' => 'Performance'],
                'average_rating' => $averageRating,
                'total_shares' => $totalShares,
                'total_leads' => $totalLeads
            ]);        
    }    
}
