<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

use Quill\Factories\CoreFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\ServiceFactory;

/**
 * Description of PushNotification
 *
 * @author harinder
 */
class PushNotification {

    public function __construct() {
        
        // Instantiate models.
        $this->models = ModelFactory::boot(array('Device', 'PushNotification'));
        // Instantiate core.
        $this->core = CoreFactory::boot(array('Http'));
        // Instantiate services.
        $this->services = ServiceFactory::boot(array('Apns'));    
        
        $this->config = load_config_one('app', array('path', 'url'));
    }       
    
    public function sendToUserDevices($userId, Array $data) {
        $data['extra'] = (!empty($data['extra']))? $data['extra'] : array();
        $_devices = $this->models->device->getByUserId($userId);
        if (!empty($_devices)) {

            foreach ($_devices as $_device) {

                if ($_device['platform'] == 'iOS') {
                    $_notification = array();
                    $notificationDataArray = array( 'title' => $data['title'], 'body' => $data['message'], 'badge' => 1, 'icon' => 'cart_only', 'color' => '#ff6c00') + $data['extra'];
                    $notification = array();
                    $notificationBody = array();
                    $notificationBody['aps'] = array(
                        'alert' => $notificationDataArray,
                        'sound' => 'default'
                    );

                    $_data['platform'] = $_device['platform'];
                    $_data['notification_id'] = $_device['notification_id'];
                    $_data['body'] = $notificationBody;

                    $notification['data'] = json_encode($_data);

                    $_notification = $this->services->apns->sendPushNotification($notificationBody, $_device['notification_id']);

                    if ($_notification) {

                        $notification['status'] = 1;
                        $this->models->pushNotification->save($notification);
                    } else {

                        $notification['status'] = 0;
                        $this->models->pushNotification->save($notification);
                    }
                } elseif ($_device['platform'] == 'Android') {


                    $registationIds[] = $_device['notification_id'];
                }
            }

            if (!empty($registationIds)) {
                $notificationDataArray = array( 'title' => $data['title'], 'message' => $data['message'], 'badge' => 1, 'icon' => 'cart_only', 'color' => '#ff6c00') + $data['extra'];
                $notification = array();

                $_data['platform'] = 'Android';
                $_data['notification_ids'] = $registationIds;
                $_data['body'] = $notificationDataArray;

                $notificationData = array('registration_ids' => $_data['notification_ids'], 'data' => $_data['body']);

                $notifications = $this->core->http->post($this->config['gcm_url'], $notificationData, $this->_getGcmHeader(), TRUE);

                if (json_decode($notifications, true)['success'] == count($_data['notification_ids'])) {

                    $_data['response'] = $notifications;
                    $notification['data'] = json_encode($_data);
                    $notification['status'] = 1;

                    $this->models->pushNotification->save($notification);
                } else {

                    $_data['response'] = $notifications;
                    $notification['data'] = json_encode($_data);
                    $notification['status'] = 0;
                    $this->models->pushNotification->save($notification);
                }
            }
        }
    }
    private function _getGcmHeader() {

        return array(
            'Authorization: key= ' . $this->config['gcm_api_key'],
            'Content-Type: application/json'
        );
    }
}
