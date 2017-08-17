<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomerReview;
use App\Models\Company;

class ReviewController {
    
    public function showReview(Request $request, $referralCode) {
        
        $customerReview = CustomerReview::where('referral_code', $referralCode)->first();
        $customerReview->load(['company']);
        return view('review')->with(
            [
                'review' => $customerReview
                
            ]);
    }
    
    public function showThumbnail($referralCode) {
        $customerReview = CustomerReview::where('referral_code', $referralCode)->first();
        
        $user = User::find($customerReview->company_id);
        $businessIndustry = $user->company->business_industry;
        $customerFriendRewardAmount = $customerReview->customer_friend_reward_amount;
        if(strpos($businessIndustry, '_') !== false) {
            $re = explode('_', $businessIndustry);
            $re1 = ucfirst($re[0]);
            $re2 = ucfirst($re[1]);
            $businessIndustry = $re1 . " " . $re2 . "s";
        } else {
            $businessIndustry = ucfirst($businessIndustry) . "s";
        }
        $off = '';
        if(!empty($customerFriendRewardAmount)) {
            $off = $customerFriendRewardAmount;
        }
        $expiryDate = !empty($customerReview->expiry_date) ? date('d/m/Y', strtotime($customerReview->expiry_date)) : '';
        $font = resource_path("assets/fonts/Aileron-Black.otf");
        $font3 = resource_path("assets/fonts/Aileron-Regular.otf");
        $font2 = resource_path("assets/fonts/AbrilFatface-Regular.ttf");
        $im = imagecreate(490, 259);
        $transparency = 25;
        imagesavealpha($im, true);
        // White background and blue text
        $bg = imagecolorallocate($im, 193, 230, 233);
        $textColor = imagecolorallocate($im, 33, 33, 33);
        $text = "{$user->company->business_name} Are";
        $text2 = "Great {$businessIndustry}";
        $text3 = "Check Out My Review!";
        $text4 = "{$off} OFF";
        $text5 = "BY MENTIONING THIS UNIQUE CODE:";
        $text6 = "Rfer{$customerReview->referral_code}"; 
        $text7 = "Call {$user->mobile_number}";
        $text8 = "Offer Expires On {$expiryDate}";
        $angle = 0;
        
        // Get image Width
        $imageWidth = imagesx($im);

        // Get Bounding Box Size
        $textBox = imagettfbbox(25,$angle,$font,$text);
        $textBox2 = imagettfbbox(25,$angle,$font,$text2);
        $textBox3 = imagettfbbox(25,$angle,$font,$text3);
        $textBox4 = imagettfbbox(30,$angle,$font,$text4);
        $textBox5 = imagettfbbox(11,$angle,$font,$text5);
        $textBox6 = imagettfbbox(11,$angle,$font,$text6);
        $textBox7 = imagettfbbox(15,$angle,$font,$text7);

        // Get your Text Width
        $textWidth = $textBox[2]-$textBox[0];
        $textWidth2 = $textBox2[2]-$textBox2[0];
        $textWidth3 = $textBox3[2]-$textBox3[0];
        $textWidth4 = $textBox4[2]-$textBox4[0];
        $textWidth5 = $textBox5[2]-$textBox5[0];
        $textWidth6 = $textBox6[2]-$textBox6[0];
        $textWidth7 = $textBox7[2]-$textBox7[0];

        // Calculate coordinates of the text
        $x = ($imageWidth/2) - ($textWidth/2);
        $x2 = ($imageWidth/2) - ($textWidth2/2);
        $x3 = ($imageWidth/2) - ($textWidth3/2);
        $x4 = ($imageWidth/2) - ($textWidth4/2);
        $x5 = ($imageWidth/2) - ($textWidth5/2);
        $x6 = ($imageWidth/2) - ($textWidth6/2);
        $x7 = ($imageWidth/2) - ($textWidth7/2);

        // Write the string at the top left
        //imagestring($im, 5, 110, 110, 'Hello world!', $textColor);
        imagettftext($im, 25, 0, $x, 40, $textColor, $font, $text);
        imagettftext($im, 25, 0, $x2, 80, $textColor, $font, $text2);
        imagettftext($im, 25, 0, $x3, 120, $textColor, $font, $text3);
        imagettftext($im, 30, 0, $x4, 170, $textColor, $font2, $text4);
        imagettftext($im, 11, 0, $x5, 190, $textColor, $font3, $text5);
        imagettftext($im, 11, 0, $x6, 210, $textColor, $font3, $text6);
        if(!empty($user->mobile_number)) {
            imagettftext($im, 15, 0, $x7, 240, $textColor, $font, $text7);
        }
        if(!empty($expiryDate)) {
            imagettftext($im, 9, 0, 330, 255, $textColor, $font3, $text8);
        }

        // Output the image
        header('Content-type: image/png');

        imagepng($im);
        imagedestroy($im);
    }
    
    public function thankYou($businessName=null) {
        return view('thank-you')->with(
            [
                'bussiness_name' => $businessName

            ]);
    }
    
    public function thankYouShared($referralCode=null) {
        $review = CustomerReview::where('referral_code', $referralCode)->first();

        return view('thank-you')->with(
            [
                'review' => $review

            ]);
    }
}
