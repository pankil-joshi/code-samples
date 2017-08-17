<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\CompanySetting;

class SettingController {

    public function saveSettings(Request $request) {

        $requestData = $request->all();
        $validator = $this->validator($requestData);

        $user = \Auth::user();
        $requestData['company_id'] = $user->company_id;
        $requestData['customer_reward'] = !empty($requestData['customer_reward'])? $requestData['customer_reward'] : '0';
        $requestData['customer_friend_reward'] = !empty($requestData['customer_friend_reward'])? $requestData['customer_friend_reward'] : '0';
        
        $companySetting = CompanySetting::updateOrCreate(array('company_id' => $user->company_id), $requestData);
        
        return output()->json()->success($companySetting);
    }

    protected function validator(array $data) {

        return Validator::make($data, [
                    'lead_full_name' => 'required|max:255',
                    'lead_mobile_number' => 'required|max:255',
                    'lead_email' => 'email|max:255'
        ]);
    }

}
