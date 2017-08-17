<?php


namespace App\Http\Controllers\Account;

use App\Models\CompanySetting;

class SettingController {
    
    public function index() {
        $user = \Auth::user();
        $companySetting = new CompanySetting();
        $settings = $companySetting->get($user->company_id);
        
        return view('account.setting')->with(
            [
                'meta' => ['title' => 'Settings'],
                'settings' => $settings
            ]);        
    }
}
