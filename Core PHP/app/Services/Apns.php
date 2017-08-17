<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

/**
 * Description of APNS
 *
 * @author harinder
 */
class Apns {
    
    public function sendPushNotification($data, $device) {

        $app = load_config_one('app', array('path'));

        //Setup stream (connect to Apple Push Server)
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'passphrase', $app['ios_cert_passphrase']);
        stream_context_set_option($ctx, 'ssl', 'local_cert', $app['ios_cert_path']);
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
        stream_set_blocking($fp, 0); //This allows fread() to return right away when there are no errors. But it can also miss errors during last seconds of sending, as there is a delay before error is returned. Workaround is to pause briefly AFTER sending last notification, and then do one more fread() to see if anything else is there.

        if (!$fp) {
            //ERROR
            echo "Failed to connect (stream_socket_client): $err $errstrn";
        } else {
            $apple_expiry = time() + (90 * 24 * 60 * 60); //Keep push alive (waiting for delivery) for 90 days
            //Loop thru tokens from database
            $apple_identifier = 3;

            $payload = json_encode($data);
            //Enhanced Notification
            $msg = pack("C", 1) . pack("N", $apple_identifier) . pack("N", $apple_expiry) . pack("n", 32) . pack('H*', str_replace(' ', '', $device)) . pack("n", strlen($payload)) . $payload;
            //SEND PUSH
            fwrite($fp, $msg);

            fclose($fp);

            return true;
        }
    }
}
