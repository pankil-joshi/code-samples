<?php

namespace App\Helpers\Auth;

use Mail;
use App\Models\User;
use App\Models\UserStripeCard;
use App\Models\UserSubscription;
use App\Models\Company;
use App\Models\Role;

class UserHelper {

    public function save($data, $userId = null) {

        if (empty($userId)) {
            
            $user = $this->create($data);

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $stripeCustomer = array(
                'metadata' => array(
                    'user_id' => $user->id
                )
            );
            $authUser = \Auth::user();
            if (empty($authUser)) {

                $stripeCustomer['source'] = $data['stripe_token'];
            }

            $stripeCustomer = \Stripe\Customer::create($stripeCustomer);
            $subscription = \Stripe\Subscription::create(array(
                        "customer" => $stripeCustomer->id,
                        "plan" => $data['subscription_plan'],
            ));
            $userSubscription = array();
            $userSubscription['stripe_subscription_id'] = $subscription->id;
            $userSubscription['current_period_start'] = $subscription->current_period_start;
            $userSubscription['current_period_end'] = $subscription->current_period_end;
            $userSubscription['subscription_package_id'] = $subscription->plan->id;
            $userSubscription['subscription_status'] = ($subscription->status == 'active' || $subscription->status == 'trialing') ? 1 : 0;
            $userSubscription['stripe_subscription_status'] = $subscription->status;
            $userSubscription['trial_start'] = $subscription->trial_start;
            $userSubscription['trial_end'] = $subscription->trial_end;
            $userSubscription['user_id'] = $user->id;
            $this->addUserSubscription($userSubscription);
            if (!empty($stripeCustomer->id)) {

                $user->stripe_customer_id = $stripeCustomer->id;
                $user->save();
                
                $email = new \App\Mail\EmailVerification(new User([
                    'email_token' => $user->email_token, 
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name
                    ]));
                Mail::to($user->email)->send($email);
                
                if (!empty($stripeCustomer->sources->data[0]->id)) {

                    $card = array();
                    $card['stripe_card_id'] = $stripeCustomer->sources->data[0]->id;
                    $card['last4'] = $stripeCustomer->sources->data[0]->last4;
                    $card['brand'] = $stripeCustomer->sources->data[0]->brand;
                    $card['country'] = $stripeCustomer->sources->data[0]->country;
                    $card['address_city'] = $stripeCustomer->sources->data[0]->address_city;
                    $card['address_country'] = $stripeCustomer->sources->data[0]->address_country;
                    $card['address_line1'] = $stripeCustomer->sources->data[0]->address_line1;
                    $card['address_line2'] = $stripeCustomer->sources->data[0]->address_line2;
                    $card['address_state'] = $stripeCustomer->sources->data[0]->address_state;
                    $card['address_zip'] = $stripeCustomer->sources->data[0]->address_zip;
                    $card['name'] = $stripeCustomer->sources->data[0]->name;
                    $card['exp_month'] = $stripeCustomer->sources->data[0]->exp_month;
                    $card['exp_year'] = $stripeCustomer->sources->data[0]->exp_year;
                    $card['fingerprint'] = $stripeCustomer->sources->data[0]->fingerprint;
                    $card['user_id'] = $user->id;
                    $card['is_default'] = 1;

                    $this->addCard($card);
                }
            }
        } else {
            
            $user = $this->update($data, $userId);
        }

        return $user;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data) {

        $company = Company::create([
                    'business_name' => $data['business_name'],
                    'business_address' => $data['business_address'],
                    'business_industry' => $data['business_industry']
        ]);

        $user = User::create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'mobile_number' => $data['mobile_number'],
                    'password' => bcrypt($data['password']),
                    'company_id' => $company->id,
                    'email_token' => str_random(10)
        ]);

        $companyAdmin = Role::where('name', 'company_admin')->first();

        $user->attachRole($companyAdmin);

        return $user;
    }
    
    protected function update(array $data, $userId) {

        $user = User::find($userId);
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->mobile_number = $data['mobile_number'];
        
        if(!empty($data['password'])) $user->password = $data['password'];
        
        $user->save();
        
        $company = Company::find($user['company_id']);
        $company->business_name = $data['business_name']; 
        $company->business_address = $data['business_address']; 
        $company->business_industry = $data['business_industry'];
        $company->save();

        return $user;
    }    

    protected function addCard(array $card) {

        return UserStripeCard::create($card);
    }

    protected function addUserSubscription(array $userSubscription) {

        return UserSubscription::create($userSubscription);
    }

}
