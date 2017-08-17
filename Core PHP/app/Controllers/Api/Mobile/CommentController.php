<?php

namespace App\Controllers\Api\Mobile;

use App\Controllers\Api\Mobile\MobileBaseController as MobileBaseController;
use Quill\Factories\CoreFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\ServiceFactory;

/**
 * Comment Controller, contains all methods related to instagram comment management.
 * 
 * @package App\Controllers\Api\Mobile
 * @author Pankil Joshi <pankil@prologictechnologies.in>
 * @version 1.0
 * @uses Quill\Factories\CoreFactory
 * @uses Quill\Factories\ServiceFactory
 * @uses Quill\Factories\ModelFactory
 * @extend App\Controllers\Api\Mobile\MobileBaseController
 */
class CommentController extends MobileBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        /**
         * Required model classes instantiated.
         */
        $this->models = ModelFactory::boot(array(
                    'User',
                    'Media',
                    'Comment',
                    'MediaCommentReply',
                    'Device',
                    'PushNotification'
        ));

        /**
         * Required core classes instantiated.
         */
        $this->core = CoreFactory::boot(array('Http'));

        /**
         * Required service classes instantiated.
         */
        $this->services = ServiceFactory::boot(array('Apns', 'PushNotification'));
    }

    /**
     * This function is being called via a cron job to fetch comments posted on all instagram media active in the database.
     */
    public function saveNewComments() {

        $this->app->config(load_config_one('url')); //Load URL configuration variables from config folder.

        $media = $this->models->media->getAllActive($filter = array(), $order = array(), $offset = '', 0); // Get all active media list from the database and set it in local variable.

        /**
         * Loop through the media list.
         */
        foreach ($media as $row) {

            $user = $this->models->user->getByInstagramId($row['instagram_user_id']); //Get user details of the owner of the media from database.

            /**
             * Get latest comments from instagram posted on current media item usine instagram api and curl.
             */
            $comments = $this->core->http->get($this->app->config('instagram_api_url_v1') . 'media/' . $row['media_id'] . '/comments?access_token=' . $user['instagram_access_token']);

            $this->models->user->countCallsByAccessToken($user['instagram_access_token']);

            $comments = json_decode($comments, TRUE); //Decode the json object to php array.

            if ($comments['meta']['code'] === 200) {  //If code == 200 then, proceed otherwise handle errors.
                foreach ($comments['data'] as $commentDetails) { //Loop trought the comments fetched from the Instagram.
                    if (stristr($commentDetails['text'], $this->app->config('master_hashtag')) !== FALSE) { //If master hashtag is found in the comment then proceed else ignore.
                        $_comment = $this->models->comment->getByInstagramCommentId($commentDetails['id']); //Check from the database if comment already exists.

                        if ($_comment !== FALSE) { //If comment exists in the databse then update it else, proceed futher.
                            $commentData = array();
                            $commentData['id'] = $_comment['id'];
                            $commentData['comment_id'] = $commentDetails['id'];
                            $commentData['instagram_user_id'] = $commentDetails['from']['id'];
                            $commentData['comment_text'] = $commentDetails['text'];
                            $commentData['created_time'] = $commentDetails['created_time'];
                            $commentData['instagram_media_id'] = $row['media_id'];

                            $this->models->comment->save($commentData);
                        } else {

                            $commentData = array();
                            $commentData['comment_id'] = $commentDetails['id'];
                            $commentData['instagram_user_id'] = $commentDetails['from']['id'];
                            $commentData['comment_text'] = $commentDetails['text'];
                            $commentData['created_time'] = $commentDetails['created_time'];
                            $commentData['instagram_media_id'] = $row['media_id'];

                            $this->models->comment->save($commentData); // Save new comment in the databse.

                            $_user = $this->models->user->getByInstagramId($commentDetails['from']['id']); //Check the database if comment owner exists in the database.

                            if (!empty($_user)) { //If user exists fetch it's devices connected.
                                $_devices = $this->models->device->getByUserId($_user['id']);

                                if (!empty($_devices)) { //If devices exists for the user then, send push notification otherwise reply to the comment.
                                    foreach ($_devices as $_device) { //Loop through all user devices found.
                                        if ($_device['platform'] == 'iOS') { //If device is an iOS device then send push notification using service apns's sendPushNotification() method.
                                            $_notification = array();

                                            $notification = array();
                                            $notificationBody = array();


                                            if ($row['is_available']) { //If product is available then send push notification with success message else with message that the product is not available.
                                                $notificationBody['aps'] = array(
                                                    'alert' => array(
                                                        'title' => '',
                                                        'body' => sprintf($this->app->config('app_notification_message'), $row['title']),
                                                        'link' => $this->app->config('app_schema_url') . $row['id'],
                                                        'comment_id' => $commentDetails['id'],
                                                        'badge' => 1,
                                                        'image' => $row['image_thumbnail'],
                                                        'icon' => 'cart_only',
                                                        'color' => '#ff6c00'
                                                    ),
                                                    'sound' => 'default'
                                                );
                                            } else {

                                                $notificationBody['aps'] = array(
                                                    'alert' => array(
                                                        'title' => '',
                                                        'body' => sprintf('%s is not available right now', $row['title']),
                                                        'link' => $this->app->config('app_schema_url') . $row['id'],
                                                        'comment_id' => $commentDetails['id'],
                                                        'badge' => 1,
                                                        'image' => $row['image_thumbnail'],
                                                        'icon' => 'cart_only',
                                                        'color' => '#ff6c00'
                                                    ),
                                                    'sound' => 'default'
                                                );
                                            }

                                            $_data['platform'] = $_device['platform'];
                                            $_data['notification_id'] = $_device['notification_id'];
                                            $_data['body'] = $notificationBody;

                                            $notification['data'] = json_encode($_data);

                                            $_notification = $this->services->apns->sendPushNotification($notificationBody, $_device['notification_id']);

                                            if ($_notification) { //If push notification sent successfully then, save details with status 1 else with status 0 into push notifications table.
                                                $notification['status'] = 1;
                                                $this->models->pushNotification->save($notification);
                                            } else {

                                                $notification['status'] = 0;
                                                $this->models->pushNotification->save($notification);
                                            }
                                        } elseif ($_device['platform'] == 'Android') { //If device platform is android then add it's notification token into an array.
                                            $registationIds[] = $_device['notification_id'];
                                        }
                                    }

                                    if (!empty($registationIds)) { //If array contains android device notification tokens then, send notification to all devies at once.
                                        $notification = array();

                                        $_data['platform'] = 'Android';
                                        $_data['notification_ids'] = $registationIds;

                                        if ($row['is_available']) { //If product is available then send push notification with success message else with message that the product is not available.
                                            $_data['body'] = array('title' => '', 'message' => sprintf($this->app->config('app_notification_message'), $row['title']), 'link' => $this->app->config('app_schema_url') . $row['id'], 'comment_id' => $commentDetails['id'], 'image' => $row['image_thumbnail'], 'icon' => 'cart_only', 'color' => '#ff6c00');
                                        } else {

                                            $_data['body'] = array('title' => '', 'message' => sprintf('%s is not available right now', $row['title']), 'link' => $this->app->config('app_schema_url') . $row['id'], 'comment_id' => $commentDetails['id'], 'image' => $row['image_thumbnail'], 'icon' => 'cart_only', 'color' => '#ff6c00');
                                        }

                                        $notificationData = array('registration_ids' => $_data['notification_ids'], 'data' => $_data['body']);

                                        $notifications = $this->core->http->post($this->app->config('gcm_url'), $notificationData, $this->_getGcmHeader(), TRUE);

                                        if (json_decode($notifications, true)['success'] == count($_data['notification_ids'])) { //If push notification sent successfully then, save details with status 1 else with status 0 into push notifications table.
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

                                } else {

                                    //Reply to the comment if user is not an existing user, using product merchant's instagram access token and save the details into media comment replies table.

                                    $reply['access_token'] = $user['instagram_access_token'];

                                    $this->models->user->countCallsByAccessToken($user['instagram_access_token']);

                                    $reply['text'] = sprintf($this->app->config('loged_out_user_comment_text'), $commentDetails['from']['username'], $this->app->config('app_title'), $this->app->config('shortner_url') . $row['uid']);

                                    $replyJson = $this->core->http->post($this->app->config('instagram_api_url_v1') . 'media/' . $row['media_id'] . '/comments', $reply);

                                    $replyData = array();
                                    $replyData['reply_to_comment_id'] = $commentDetails['id'];
                                    $replyData['text'] = $reply['text'];

                                    $this->models->mediaCommentReply->save($replyData);
                                }
                            } else {
                                //Reply to the comment if user is not an existing user, using product merchant's instagram access token and save the details into media comment replies table.

                                $reply['access_token'] = $user['instagram_access_token'];

                                $this->models->user->countCallsByAccessToken($user['instagram_access_token']);

                                $reply['text'] = sprintf($this->app->config('non_app_user_comment_text'), $commentDetails['from']['username'], $this->app->config('app_title'), $this->app->config('app_title'), $this->app->config('shortner_url') . $row['uid']);

                                $replyJson = $this->core->http->post($this->app->config('instagram_api_url_v1') . 'media/' . $row['media_id'] . '/comments', $reply);

                                $replyData = array();
                                $replyData['reply_to_comment_id'] = $commentDetails['id'];
                                $replyData['text'] = $reply['text'];

                                $this->models->mediaCommentReply->save($replyData);
                            }
                        }
                    }
                }
            } else {

                if ($comments['meta']['error_type'] === 'APINotFoundError') { //If the error_type is APINotFoundError then mark the comment is_deleted in the database.
                    $mediaData['id'] = $row['id'];
                    $mediaData['is_deleted'] = 1;

                    $this->models->media->save($mediaData);
                }
            }
        }
    }

    /**
     * Get header data to be sent with new google push notification(GCM) request.
     * 
     * @return array
     */
    private function _getGcmHeader() {

        return array(
            'Authorization: key= ' . $this->app->config('gcm_api_key'),
            'Content-Type: application/json'
        );
    }

}
