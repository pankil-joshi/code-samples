<?php


namespace App\Http\Controllers\Account;

use App\Models\CustomerReview;

class CustomerController {
    
    public function listAll() {
        $user = \Auth::user();
        $customerReview = new CustomerReview();
        $customers = $customerReview->listAllByCompanyId($user->company_id);
        
        return view('account.customer.list')->with(
            [
                'meta' => ['title' => 'Customer\'s List'],
                'customers' => $customers
            ]);        
    }
}
