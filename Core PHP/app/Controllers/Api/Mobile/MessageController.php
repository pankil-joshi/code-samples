<?php

namespace App\Controllers\Api\Mobile;

use App\Controllers\Api\Mobile\MobileBaseController as MobileBaseController;
use Quill\Factories\CoreFactory;
use Quill\Factories\ModelFactory;
use Quill\Factories\ServiceFactory;
use Quill\Exceptions\BaseException;
use Quill\Exceptions\ValidationException;

/**
 * Description of MessageController
 *
 * @author harinder
 */
class MessageController extends MobileBaseController {

    function __construct($app = NULL) {

        parent::__construct($app);

        $this->models = ModelFactory::boot(array(
                    'MessageRecent',
                    'Message',
                    'MessageRecipient',
                    'MessagesThreadDetails',
                    'User',
                    'OrderItem'
        ));

        $this->core = CoreFactory::boot(array('Response'));
        $this->services = ServiceFactory::boot(array('PushNotification'));
    }

    public function postMessage($threadId = '') {

        $logData = array();
        $logData['post'] = $this->jsonRequest;

        $this->userLogger->log('info', 'User requested to post a message on thread.', $this->app->user['id'], $logData);

        $request = $this->jsonRequest;

        $rules = [
            'required' => [['recipient_id'], ['text']],
            'numeric' => [['recipient_id']]
        ];

        $v = new \Quill\Validator($request, array('text', 'attributes', 'recipient_id', 'product_id', 'order_id', 'type', 'dispute'));
        $v->rules($rules);

        if ($v->validate()) {

            $message = $v->sanatized();
            $senderUser = $this->models->user->getById($this->app->user['id']);
            $recipientUser = $this->models->user->getById($message['recipient_id']);

            if (!empty($message['product_id']))
                $threadDetails['product_id'] = $message['product_id'];
            unset($message['product_id']);
            if (!empty($message['order_id']))
                $threadDetails['order_id'] = $message['order_id'];
            unset($message['order_id']);
            if (!empty($message['type']))
                $threadDetails['type'] = $message['type'];
            unset($message['type']);
            if (!empty($message['dispute']))
                $threadDetails['dispute'] = $message['dispute'];
            unset($message['dispute']);

            $message['sender_id'] = $this->app->user['id'];
            if (empty($threadId)) {

                if (!empty($threadDetails['order_id'])) {
                    if (!empty($threadDetails['dispute'])) {
                        $threadId = $this->models->messagesThreadDetails->getDisputeIdByOrderId($threadDetails['order_id'], $this->app->user['id'], $message['recipient_id']);
                    } else {
                        $threadId = $this->models->messagesThreadDetails->getThreadIdByOrderId($threadDetails['order_id'], $this->app->user['id'], $message['recipient_id']);
                    }

                    $threadDetails['product_id'] = $this->models->orderItem->getOneBySuborderId($threadDetails['order_id'])['media_id'];
                } elseif (!empty($threadDetails['product_id'])) {

                    $threadId = $this->models->messagesThreadDetails->getThreadIdByProductId($threadDetails['product_id'], $this->app->user['id']);
                }
            }

            if ($message['recipient_id'] == $this->app->user['id']) {

                throw new BaseException('Bad request');
            }

            $recipientId = $message['recipient_id'];

            unset($message['recipient_id']);

            if (!empty($threadId)) {

                $thread = $this->models->message->getRootById($threadId, $this->app->user['id']);

                $participants = array($thread['sender_id'], $thread['recipient_id']);

                if (!empty($thread && in_array($recipientId, $participants))) {

                    unset($message['recipient_id']);

                    $message['is_root'] = 0;
                    $message['reply_to_message_id'] = $threadId;

                    $messageId = $this->models->message->save($message);

                    if (!empty($messageId)) {

                        $recipient['message_id'] = $messageId;
                        $recipient['recipient_id'] = $recipientId;
                        $recipient['thread_id'] = $threadId;

                        $receiptId = $this->models->messageRecipient->save($recipient);
                        
                        if ($recipientUser['notify_push_new_message']) {

                            $data = array('title' => 'New message', 'message' =>  $senderUser['first_name'] . ' ' . $senderUser['last_name'] . ': ' . $message['text'], 'extra' => array('link' => 'ipaid://messageForm.html', 'image' => $senderUser['instagram_profile_picture']));
                            $this->services->pushNotification->sendToUserDevices($recipient['recipient_id'], $data);
                        }
                    }
                } else {

                    throw new BaseException('Bad request');
                }
            } else {

                $message['is_root'] = 1;

                $messageId = $this->models->message->save($message);

                $threadDetails['thread_id'] = $messageId;
                $threadDetails['status'] = 'open';
                $threadDetails['first_user_id'] = $this->app->user['id'];
                $threadDetails['second_user_id'] = $recipientId;

                $threadDetailsId = $this->models->messagesThreadDetails->save($threadDetails);

                $message['id'] = $messageId;
                $message['reply_to_message_id'] = $messageId;

                $this->models->message->save($message);

                if (!empty($messageId)) {

                    $recipient['message_id'] = $messageId;
                    $recipient['recipient_id'] = $recipientId;
                    $recipient['thread_id'] = $messageId;

                    $receiptId = $this->models->messageRecipient->save($recipient);

                    if ($recipientUser['notify_push_new_message']) {

                        $data = array('title' => 'New message', 'message' => $senderUser['first_name'] . ' ' . $senderUser['last_name'] . ': ' . $message['text'], 'extra' => array('link' => 'ipaid://messageForm.html', 'image' => $senderUser['instagram_profile_picture']));
                        $this->services->pushNotification->sendToUserDevices($recipient['recipient_id'], $data);
                    }
                }
            }

            if (!empty($messageId) && isset($receiptId)) {

                $data['message'] = $message;

                echo $response = $this->core->response->json($data, FALSE);

                $logData = array();
                $logData['response'] = $response;

                $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
            } else {

                $this->userLogger->log('info', 'Nothing to change.', $this->app->user['id']);

                throw new BaseException('Nothing to change.');
            }
        } else {

            $logData = array();
            $logData['errors'] = $v->errors();

            $this->userLogger->log('error', 'Data validation failed', $this->app->user['id'], $logData);

            throw new ValidationException($v->errors());
        }
    }

    public function getAllRecent() {

        $this->userLogger->log('info', 'User requested to get recent messages.', $this->app->user['id']);
        $filter = array('key' => $this->request->get('filter'), 'start_date' => $this->request->get('startDate'), 'end_date' => $this->request->get('endDate'));
        $recentMessages = $this->models->message->getRecentByUserId($this->app->user['id'], '', '', $options = array('filter' => $filter, 'order_by' => $this->request->get('orderBy'), 'order' => $this->request->get('order')));

        foreach ($recentMessages as $index => $thread) {

            if ($this->app->user['id'] == $thread['sender_id']) {

                $user = $this->models->user->getById($thread['recipient_id']);
            } else {

                $user = $this->models->user->getById($thread['sender_id']);
            }
            $recentMessages[$index]['second_user'] = $user;
        }

        if ($recentMessages) {

            $data['recentMessages'] = $recentMessages;

            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function getThread($id) {

        $this->userLogger->log('info', 'User requested to get thread messages.', $this->app->user['id']);
        $this->models->messageRecipient->markThreadRead($id, $this->app->user['id']);
        $thread = $this->models->message->getAllByThreadId($id, $this->app->user['id']);

        if ($thread) {

            $data['thread'] = $thread;
            $data['thread_details'] = $this->models->messagesThreadDetails->getByThreadId($id, $this->app->user['id']);
            if ($this->app->user['id'] == $thread[0]['sender_id']) {

                $user = $this->models->user->getById($thread[0]['recipient_id']);
            } else {

                $user = $this->models->user->getById($thread[0]['sender_id']);
            }
            $data['second_user'] = $user;
            echo $response = $this->core->response->json($data, FALSE);

            $logData = array();
            $logData['response'] = $response;

            $this->userLogger->log('info', 'Response returned to user', $this->app->user['id'], $logData);
        } else {

            $this->userLogger->log('error', 'Resource not found', $this->app->user['id']);

            throw new BaseException('Resource not found');
        }
    }

    public function markRead($messageId) {

        $recipient['read_at'] = gmdate('Y-m-d H:i:s');
        $recipient['message_id'] = $messageId;

        $_recipient = $this->models->messageRecipient->save($recipient);

        if ($_recipient) {

            $data['recipient'] = $recipient;

            echo $response = $this->core->response->json($data, FALSE);
        }
    }

}
