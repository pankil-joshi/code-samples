<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\User;
use App\Models\CustomerReview;
use Twilio\Rest\Client;
use Mail;

class LeadController {

    public function addLead(Request $request) {

        $requestData = $request->all();
        $validator = $this->validator($requestData);
        if ($validator->fails())
            return output()->json()->error(array('message' => $validator->messages(), 'type' => 'bad_request'), 200);

        if(!empty($requestData['review_id'])) {
            
            $requestData['customer_review_id'] = $requestData['review_id'];
            $customerReview = CustomerReview::find($requestData['review_id']);
            $requestData['company_id'] = $customerReview->company_id;
        }
        if(!empty($requestData['referral_code'])) {
            
            $referralCode = str_ireplace('rfer', '', $requestData['referral_code']);

            $customerReview = CustomerReview::where('referral_code', $referralCode)->first();
            
            if(empty($customerReview)) {
                
                return output()->json()->error(array('message' => 'Referral code does not exist.', 'type' => 'bad_request'), 200);
            }
            $requestData['customer_review_id'] = $customerReview->id;
            $requestData['company_id'] = $customerReview->company_id;
        }  
        if($customerReview->expirable == '1' && !empty($customerReview->expiry_date)) {
            $current_str = strtotime(date('d-m-Y'));
            $expiry = date('d-m-Y', strtotime($customerReview->expiry_date));
            $expiry = strtotime($expiry);
            if($current_str >= $expiry) {
                return output()->json()->error(array('message' => 'Customer referral code has been expired', 'type' => 'bad_request'), 200);
            }
        }
        $lead = Lead::create($requestData);
        $user = User::find($requestData['company_id']);
        $requestData['company_mobile_number'] = $user->mobile_number;
        $requestData['company_email'] = $user->email;
        $requestData['user_name'] = $user->first_name;
        $requestData['company_name'] = $user->company->business_name;
        if(!empty($lead) && !empty($user)) {
            $this->sendSMSToCustomer($requestData);
            if(!empty($requestData['lead_email'])) {
                $this->sendEmailToCustomer($requestData);
            }            
            $this->sendEmail($requestData);
            if(!empty($requestData['company_mobile_number'])) {
                $this->sendSMS($requestData);
            }
        }
        return output()->json()->success($lead);
    }

    protected function validator(array $data) {

        return Validator::make($data, [
                    'lead_full_name' => 'required|max:255',
                    'lead_mobile_number' => 'required|max:255'
        ]);
    }
    
   protected function sendSMSToCustomer($data) {

        $link =  config('app.url');       
        $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        $body = "Hi {$data['lead_full_name']},\n\n"
        . "Thank you for your inquiry. It has been sent to {$data['company_name']}.  They should be in touch shortly.\n\n"
        . "Alternatively, you can contact {$data['company_name']} directly on {$data['company_mobile_number']}, at your convenience.";
        $sms = $client->account->messages->create(
            $data['lead_mobile_number'], array(
                'from' => config('services.twilio.number'),
                'body' => $body
            )
        );
    }

    protected function sendEmailToCustomer($data) {
        
        $link =  config('app.url');
        $greetings = "Hi {$data['lead_full_name']},";
        $notification = "<p>Thank you for your inquiry. It has been sent to {$data['company_name']}.  They should be in touch shortly.</p>"
        . "Alternatively, you can contact {$data['company_name']} directly on {$data['company_mobile_number']}, at your convenience.";

        $email = new \App\Mail\Notification($greetings, $notification);
        $email->subject('Thank you for your inquiry');

        Mail::to($data['lead_email'])->send($email);
    }
    
    protected function sendSMS($data) {

        $link =  config('app.url');       
        
        $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        $body = "Hi {$data['user_name']},\n\n"
        . "You have received a new lead, contact details are following:\n\n"
        . "Name: {$data['lead_full_name']}\nMobile Number: {$data['lead_mobile_number']}"
        . "\nEmail: {$data['lead_email']}";
        $sms = $client->account->messages->create(
            $data['company_mobile_number'], array(
                'from' => config('services.twilio.number'),
                'body' => $body
            )
        );
    }

    protected function sendEmail($data) {
        
        $link =  config('app.url');
        $greetings = "Good news {$data['user_name']}";
        $notification = '<p>You\'ve received a new lead, contact details are following:</p>'
        . '<table style="width: 100%; margin: 30px auto; text-align: center;" width="100%">'
        . '<tr style="text-align:left">'
        . '<td><b>Name:</b></td>'
        . '<td>' . $data['lead_full_name'] . '</td>'
        . '</tr>'
        . '<tr style="text-align:left">'
        . '<td><b>Mobile Number:</b></td>'
        . '<td>' . $data['lead_mobile_number'] . '</td>'
        . '</tr>'
        . '<tr style="text-align:left">'
        . '<td><b>Email:</b></td>'
        . '<td>' . $data['lead_email'] . '</td>'
        . '</tr>'
        . '</table>';

        $email = new \App\Mail\Notification($greetings, $notification);
        $email->subject('You\'ve received a new lead');

        Mail::to($data['company_email'])->send($email);
    }

}
