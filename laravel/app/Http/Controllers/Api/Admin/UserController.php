<?php


namespace App\Http\Controllers\Api\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Helpers\Auth\UserHelper;
use App\Models\User;

class UserController {
    
    public function save(Request $request, $userId = null) {

        $requestData = $request->all();
        $validator = $this->validator($requestData, $userId);
        if ($validator->fails())
            return output()->json()->error(array('message' => $validator->messages(), 'type' => 'bad_request'), 200);

        $user = (new UserHelper())->save($requestData, $userId);

        if (!empty($user)) {

            return output()->json()->success($user);
        }
    }
    
    public function delete($userId) {

        $user = User::find($userId);
        
        if(empty($user['id'])) {
            
            return output()->json()->error(array('message' => 'Invalid user ID.', 'type' => 'bad_request'), 200);
        } elseif($user->roles[0]['name'] == 'super_admin') {
            
            return output()->json()->error(array('message' => 'You can not delete a superadmin.', 'type' => 'bad_request'), 200);
        } else {
            
           $user->delete(); 
           
            return output()->json()->success(array('message' => 'User deleted successfully'));           
        }
    }    
    
    protected function validator(array $data, $userId = null) {
        
        $rules = [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'business_name' => 'required|max:255',
                    'business_address' => 'required|max:255',
                    'business_industry' => 'required',
                    'mobile_number' => 'required|max:20',
                    'email' => 'required|email|max:255|unique:users',
                    
        ];
        
        if(empty($userId)) {
            
            $rules['subscription_plan'] = 'required|in:1,2';
            $rules['password'] = 'required|min:6';
            $rules['email'] = 'required|email|max:255|unique:users';
        } else {
            
            $rules['email'] = 'required|email|max:255|unique:users,id,' . $userId;
            
            if(!empty($data['password']))
                $rules['password'] = 'min:6';
        }

        return Validator::make($data, $rules);
    }    
  
}
