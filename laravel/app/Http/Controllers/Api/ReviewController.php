<?php

namespace App\Http\Controllers\Api;

use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\CustomerReview;
use App\Models\User;
use Twilio\Rest\Client;
use App\Mail\Notification;

class ReviewController {

    public function addCustomer(Request $request) {

        $requestData = $request->all();
        
        $validator = $this->validator($requestData);
        if ($validator->fails())
            return output()->json()->error(array('message' => $validator->messages(), 'type' => 'bad_request'), 200);

        $user = \Auth::user();

        $requestData['company_id'] = $user->company_id;
        $requestData['business_name'] = $user->company->business_name;
        $requestData['account_name'] = ucfirst($user['first_name']) . ' ' . ucfirst($user['last_name']);
        $requestData['referral_code'] = substr(md5(time()), 0, 6);
        //$requestData['customer_reward'] = !empty($requestData['customer_reward'])? $requestData['customer_reward'] : '0';
        //$requestData['customer_friend_reward'] = !empty($requestData['customer_friend_reward'])? $requestData['customer_friend_reward'] : '0';
        $requestData['customer_email'] = 'neeraj@smartive.xyz';
        $customerReview = $this->addCustomerReview($requestData);
        $requestData['review_id'] = $customerReview->id;
        if (!empty($customerReview)) {

            $this->sendSMS($requestData);
            $this->sendEmail($requestData);
        }

        return output()->json()->success($customerReview);
    }
    
    public function remindCustomer($customerId) {
        
        $user = \Auth::user();
        $review = CustomerReview::find($customerId);
        $review['company_id'] = $user->company_id;
        $review['business_name'] = $user->company->business_name;
        $review['account_name'] = ucfirst($user['first_name']) . ' ' . ucfirst($user['last_name']);
        $review['customer_reward'] = !empty($requestData['customer_reward'])? $requestData['customer_reward'] : '0';
        $review['customer_friend_reward'] = !empty($requestData['customer_friend_reward'])? $requestData['customer_friend_reward'] : '0';
        $review['reminder'] = true;
        $review['review_id'] = $review->id;

        if (!empty($review)) {

            $this->sendSMS($review);
            $this->sendEmail($review);
        }
        
        $review = CustomerReview::find($customerId);
        $review->last_reminder_sent_at = date('Y-m-d H:i:s');
        $review->save();
        
        return output()->json()->success(array('messager' => 'Reminder sent successfully.'));       
    }


    public function search(Request $request) {
        
        $user = \Auth::user();
        $customerReview = new CustomerReview();
        $searchTerm = $request['term'];
        $customers = $customerReview->searchByCompanyIdByTerm($user->company_id, $searchTerm);
        
        return output()->json()->success($customers);
    }

    protected function validator(array $data) {

        return Validator::make($data, [
                    'customer_name' => 'required|max:255',
                    //'customer_email' => 'required|email|max:255',
                    'customer_mobile_number' => 'required|max:255'
        ]);
    }

    protected function addCustomerReview(array $data) {
        return CustomerReview::create($data);
    }

    protected function sendSMS($data) {
        $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        $discountText = $data['customer_friend_reward_amount'];
        //$discountText = ($data['customer_friend_reward_type'] == 'percent')? number_format($data['customer_friend_reward_amount'], 2) . '%' : '$' . number_format($data['customer_friend_reward_amount'], 2);
        $reviewLink =  config('app.url') . "testimonial/" . $data['referral_code'];
        $offerText = $reminderText = '';
        if(!empty($data['customer_friend_reward_amount'])) {
            $offerText = "Your friend will get a {$discountText} discount off the quote they get from us.";
        }
        
       if(!empty($data['reminder'])) {
            
            $reminderText = 'Just a friendly reminder. ';
        }
        $body = "Hi {$data['customer_name']},\n\nThanks for using {$data['business_name']}.\n\n{$reminderText}We'd love it if you wrote a short testimonial and shared it with your friends. $offerText Please visit {$reviewLink}\n\nThanks, {$data['account_name']}\n({$data['business_name']})";
        $sms = $client->account->messages->create(
                $data['customer_mobile_number'], array(
            'from' => config('services.twilio.number'),
            'body' => $body
                )
        );
    }

    protected function sendEmail($data) {
        $discountText = $data['customer_friend_reward_amount'];
        //$discountText = ($data['customer_friend_reward_type'] == 'percent')? number_format($data['customer_friend_reward_amount'], 2) . '%' : '$' . number_format($data['customer_friend_reward_amount'], 2);
        $reviewLink =  config('app.url') . "testimonial/" . $data['referral_code'];
        $offerText = $reminderText = '';
        if(!empty($data['customer_friend_reward_amount'])) {
            $offerText = "Your friend will get a {$discountText} discount off the quote they get from us.";
        }

        if(!empty($data['reminder'])) {
            
            $reminderText = 'Just a friendly reminder. ';
        }        
        $greetings = "Hi {$data['customer_name']}";
                
        $notification = "<p>Thanks for using {$data['business_name']}.</p>"
                    . "<p>{$reminderText}We would love it if you wrote a short testimonial and shared it with your friends. {$offerText} "
                    . "Please visit <a href='{$reviewLink}'>{$reviewLink}</a>.</p>"
                    . "<p>Thanks, {$data['account_name']}<br />({$data['business_name']})</p>";
                    
        $email = new \App\Mail\Notification($greetings, $notification);
        $email->subject('Thanks for your business');
        
        Mail::to($data['customer_email'])->send($email);
    }

    public function submitReview(Request $request, $referralCode) {

        $requestData = $request->all();
        $validator = $this->validateReview($requestData);
        if ($validator->fails())
            return output()->json()->error(array('message' => $validator->messages(), 'type' => 'bad_request'), 200);

        $customerReview = CustomerReview::where('referral_code', $referralCode)->first();
        
        $user = User::find($customerReview->company_id);
        $requestData['company_id'] = $user->company_id;
        $requestData['business_name'] = $user->company->business_name;
        $requestData['account_mobile_number'] = $user->mobile_number;
        $requestData['account_email'] = $user->email;
        $requestData['account_name'] = ucfirst($user['first_name']) . ' ' . ucfirst($user['last_name']);        
        $requestData['customer_name'] = $customerReview['customer_name'];
        $requestData['customer_mobile_number'] = $customerReview['customer_mobile_number'];
        $requestData['customer_email'] = $customerReview['customer_email'];
        $requestData['referral_code'] = $customerReview['referral_code'];
        
        $customerReview->customer_recommend = $requestData['customer_recommend'];
        $customerReview->customer_rating = $requestData['customer_rating'];
        $customerReview->customer_testimonial = $requestData['customer_testimonial'];
        $customerReview->customer_feedback = $requestData['customer_feedback'];
        $customerReview->title = $requestData['customer_title'];
        $customerReview->customer_feedback = $requestData['customer_feedback'];
        $customerReview->shared = !empty($requestData['shared'])? $requestData['shared'] : null;
        $customerReview->share_method = !empty($requestData['share_method'])? $requestData['share_method'] : null;
        $customerReview->save();

        $requestData['review_id'] = $customerReview->id;

        if (!empty($customerReview)) {
            if(!empty($requestData['account_mobile_number'])) {
                $this->sendSubmitReviewSMS($requestData);
            }
            $this->sendSubmitReviewEmail($requestData);
        }

        return output()->json()->success($customerReview);
    }
    
    public function shareReview(Request $request, $referralCode) {

        $requestData = $request->all();
        $validator = $this->validateReview($requestData);
        if ($validator->fails())
            return output()->json()->error(array('message' => $validator->messages(), 'type' => 'bad_request'), 200);

        $customerReview = CustomerReview::where('referral_code', $referralCode)->first();
        
        $user = User::find($customerReview->company_id);
        $requestData['company_id'] = $user->company_id;
        $requestData['business_name'] = $user->company->business_name;
        $requestData['account_mobile_number'] = $user->mobile_number;
        $requestData['account_email'] = $user->email;
        $requestData['account_name'] = ucfirst($user['first_name']) . ' ' . ucfirst($user['last_name']);        
        $requestData['customer_name'] = $customerReview['customer_name'];
        $requestData['customer_mobile_number'] = $customerReview['customer_mobile_number'];
        $requestData['referral_code'] = $customerReview['referral_code'];
        $requestData['customer_friend_reward_amount'] = $customerReview['customer_friend_reward_amount'];
        $requestData['customer_email'] = $requestData['email'];

        $customerReview->save();

        $requestData['review_id'] = $customerReview->id;

        if (!empty($customerReview)) {
            $this->sendShareEmail($requestData);
        }

        return output()->json()->success($customerReview);
    }
    
    protected function sendShareEmail($data) {
        $discountText = $data['customer_friend_reward_amount'];
        //$discountText = ($data['customer_friend_reward_type'] == 'percent')? number_format($data['customer_friend_reward_amount'], 2) . '%' : '$' . number_format($data['customer_friend_reward_amount'], 2);
        $reviewLink =  config('app.url') . "testimonial/" . $data['referral_code'];
        $offerText = $reminderText = '';
        if(!empty($data['customer_friend_reward_amount'])) {
            $offerText = "Your friend will get a {$discountText} discount off the quote they get from us.";
        }
        $greetings = "Hi";
                
        $notification = "<p>{$data['customer_name']} has recently used {$data['business_name']} and thought you may be interested in the below exclusive offer.</p>"
                    . "<p>Get {$discountText } off with {$data['business_name']} by quoting their unique code {$data['referral_code']} or visit <a href='{$reviewLink}'>{$reviewLink}</a>."
                    . "<p>Thanks, {$data['account_name']}<br />({$data['business_name']})</p>";
                    
        $email = new \App\Mail\Notification($greetings, $notification);
        $subject = "Get " . $discountText . " off with " . $data['business_name'];
        $email->subject($subject);
        $emails = trim($data['customer_email']);
        $emails = explode(',', $emails);
        Mail::to($emails)->send($email);
    }
    
    protected function validateReview(array $data) {

        return Validator::make($data, [
                    

        ]);
    }    

    protected function sendSubmitReviewSMS($data) {

        $reviewLink =  config('app.url') . "testimonial/" . $data['referral_code'];
        $recommended = ($data['customer_recommend'] == 1)? 'Yes' : 'No';        
        
        $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        
        if (!empty($data['shared'])) {

            $body = "Good news {$data['account_name']},\n\n" 
            . ucfirst($data['customer_name']) . " just wrote and shared the following testimonial:\n\n"
            . "Recommend: {$recommended}\nStar Rating: {$data['customer_rating']}\nTestimonial Summary: {$data['customer_feedback']}\nTestimonial: {$data['customer_testimonial']}";
            $sms = $client->account->messages->create(
                    $data['account_mobile_number'], array(
                'from' => config('services.twilio.number'),
                'body' => $body
                    )
            );

            $body = "Hi {$data['customer_name']},\n\n"
            . "Thank you for providing your testimonial and promoting us.\n\n"
            . "Please share this email with your friends, who would be interested in our service, and ensure they visit the below link:\n\n"
                    . "{$reviewLink} Let us know if we can help in the future!\n\n"
                    . "{$data['account_name']}\n{$data['account_mobile_number']}\n{$data['business_name']}";
            $sms = $client->account->messages->create(
                    $data['customer_mobile_number'], array(
                'from' => config('services.twilio.number'),
                'body' => $body
                    )
            );
        } elseif($data['customer_recommend'] === '0') {

            $body = "Hi {$data['account_name']},\n\n"
            . "you just received the following feedback from {$data['customer_name']}:\n\n"
            . "Recommend: {$recommended}\nComments: {$data['customer_feedback']}";
            $sms = $client->account->messages->create(
                    $data['account_mobile_number'], array(
                'from' => config('services.twilio.number'),
                'body' => $body
                    )
            );
        }
    }

    protected function sendSubmitReviewEmail($data) {
        
        $reviewLink =  config('app.url') . "testimonial/" . $data['referral_code'];
        $recommended = ($data['customer_recommend'] == 1)? 'Yes' : 'No';
             
        if (!empty($data['shared'])) {

            $greetings = "Good news {$data['account_name']}";
            $notification = '<p>' . ucfirst($data['customer_name']) . ' just wrote and shared the following testimonial:</p>'
            . '<table style="width: 100%; margin: 30px auto; text-align: center;" width="100%">'
            . '<tr style="text-align:left">'
            . '<td><b>Recommend:</b></td>'
            . '<td>' . $recommended . '</td>'
            . '</tr>'
            . '<tr style="text-align:left">'
            . '<td><b>Star Rating:</b></td>'
            . '<td>' . $data['customer_rating'] . '</td>'
            . '</tr>'
            . '<tr style="text-align:left">'
            . '<td><b>Testimonial Summary:</b></td>'
            . '<td>' . $data['customer_feedback'] . '</td>'
            . '</tr>'                    
            . '<tr style="text-align:left">'
            . '<td><b>Testimonial:</b></td>'
            . '<td>' . $data['customer_testimonial'] . '</td>'
            . '</tr>'
            . '</table>';

            $email = new \App\Mail\Notification($greetings, $notification);
            $email->subject('You\'ve received a new testimonial');

            Mail::to($data['account_email'])->send($email);

            $greetings = "Hi {$data['customer_name']}";
            $notification = "<p>Thank you for providing a testimonial of {$data['business_name']} and promoting us.</p>"
                . "<p>Please share this email with your friends, who would be interested in our service, and ensure they visit the below link:</p>"
                . "<p>{$reviewLink} </p>"
                . "<p>Let us know if we can help in the future!</p>"
                . "<p>{$data['account_name']}<br />{$data['account_mobile_number']}<br />{$data['business_name']}";

            
            $email = new \App\Mail\Notification($greetings, $notification);
            $email->subject('Thanks for the testimonial');

            Mail::to($data['customer_email'])->send($email);
            
        } elseif($data['customer_recommend'] === '0') {

            $greetings = "Hi {$data['account_name']}";
            $notification = "<p>You just received the following feedback from {$data['customer_name']}:</p>"            
            . '<table style="width: 100%; margin: 30px auto; text-align: center;" width="100%">'
            . '<tr style="text-align:left">'
            . '<td><b>Recommend:</b></td>'
            . '<td>' . $recommended . '</td>'
            . '</tr>'
            . '<tr style="text-align:left">'       
            . '<td><b>Comments:</b></td>'
            . '<td>' . $data['customer_feedback'] . '</td>'
            . '</tr>'
            . '</table>';
            $email = new \App\Mail\Notification($greetings, $notification);
            $email->subject('You\'ve received feedback');

            Mail::to($data['account_email'])->send($email);
        }
    }

}
