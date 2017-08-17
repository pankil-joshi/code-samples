<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controllers\Api\Mobile;

use App\Controllers\Api\Mobile\MobileBaseController as MobileBaseController;

use Quill\Factories\CoreFactory; 
use Quill\Factories\ServiceFactory;
use Quill\Factories\ModelFactory;
use Quill\Exceptions\BaseException;

/**
 * Description of SubscriptionController
 *
 * @author harinder
 */
class SubscriptionController extends MobileBaseController{

    function __construct($app = NULL) {
        
        parent::__construct($app);
         
        $this->models = ModelFactory::boot(array(
            'SubscriptionPackage',
            'SubscriptionTransactionFeeRule'
            )); 
        
        $this->core = CoreFactory::boot(array('Response'));
        
        $this->services = ServiceFactory::boot(array('Stripe'));        
                
    }
    
    public function savePackage($id = null) {
        
        $_subscriptionPackage = $this->jsonRequest;
        
        $transactionFeeRules = $_subscriptionPackage['transaction_fee_rules'];
        unset($_subscriptionPackage['transaction_fee_rules']);
        
        $subscriptionPackageId = ($id !== NULL)? $id : $this->models->subscriptionPackage->save($_subscriptionPackage);
        
        if(isset($transactionFeeRules)) {
            
            $transactionFeeRules['subscription_package_id'] = $subscriptionPackageId;
            
            $this->models->subscriptionTransactionFeeRule->save($transactionFeeRules);
            
        }
        
    }
    
    public function listSubscriptions() {

        $this->userLogger->log('info', 'User requested to get subscription list.', $this->app->user['id']);        

        $subscriptions = $this->models->subscriptionPackage->getAll();

        if($subscriptions){
            
            $data['subscriptions'] = $subscriptions;
            
            echo $response = $this->core->response->json($data, FALSE);
                
            $logData = array();
            $logData['response'] = $response;
            
            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
            
        } else {       
                    
            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
            
        }
    }
    
    public function createStripeSubscriptionPlans() {

        
        $request = $this->jsonRequest;

        $subscriptions = $this->models->subscriptionPackage->getAllPlans();

        if($subscriptions){
            
            foreach ($subscriptions as $subscription) {
                
                $createPlan = $this->services->stripe->createPlan($subscription['rate'], $subscription['rate_currency_code'], $subscription['subscription_interval'], $subscription['name'], $subscription['id']);
                
            }
            
        } else {                          

            throw new BaseException('Resource not found');
            
        }
    }    
    
}
