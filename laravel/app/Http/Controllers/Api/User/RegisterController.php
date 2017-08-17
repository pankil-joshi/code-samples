<?php

namespace App\Http\Controllers\Api\User;

use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Auth\UserHelper;
use App\Models\User;

class RegisterController {

    public function register(Request $request, UserHelper $user) {

        $requestData = $request->all();
        $validator = $this->validator($requestData);
        if ($validator->fails())
            return output()->json()->error(array('message' => $validator->messages(), 'type' => 'bad_request'), 200);

        $user = $user->save($requestData);

        if (!empty($user)) {

            return output()->json()->success($user);
        }
    }

    public function checkEmail(Request $request) {

        $requestData = $request->all();
        $validator = Validator::make($requestData, ['email' => 'required|email|max:255|unique:users']);

        if ($validator->fails()) {

            $response = $requestData['email'] . ' already exists.';
        } else {

            $response = true;
        }

        return response()->json($response);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {

        return Validator::make($data, [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'business_name' => 'required|max:255',
                    'business_address' => 'required|max:255',
                    'business_industry' => 'required',
                    'mobile_number' => 'required|max:20',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|min:6',
                    'stripe_token' => 'required',
                    'subscription_plan' => 'required|in:1,2',
        ]);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard() {
        return Auth::guard();
    }

    public function verify($token = null) {


        if (empty($token)) {

            return view('verify')->with(
                            [
                                'meta' => ['title' => 'Verify email']
            ]);
        } else {

            $user = User::where('email_token', $token)->firstOrFail()->verified();

            $email = new \App\Mail\Welcome(new User([
                'email_token' => $user->email_token,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email
            ]));
            Mail::to($user->email)->send($email);
            
            return redirect('login');
        }
    }

}
