<?php


namespace App\Http\Controllers\Account;

use App\Models\Lead;

class LeadController {
    
    public function listAll() {
        $user = \Auth::user();
        $lead = new Lead();
        $leads = $lead->listAllByCompanyId($user->company_id);
        $leads->load(['company', 'customerReview']);
        return view('account.lead.list')->with(
            [
                'meta' => ['title' => 'Lead\'s List'],
                'leads' => $leads
            ]);        
    }
    
    public function add() {
        
        return view('account.lead.add')->with(
            [
                'meta' => ['title' => 'Add a Lead']
            ]);        
    }   
}
