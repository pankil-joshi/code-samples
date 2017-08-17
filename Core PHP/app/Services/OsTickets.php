<?php

namespace App\Services;

use Quill\Factories\CoreFactory;

use Quill\Exceptions\BaseException;

class OsTickets {
    
    public $name, $email, $subject, $message, $topicId, $slaId;

    public function __construct() {

        // Instantiate core.
        $this->core = CoreFactory::boot(array('Http'));
    }

    function createTicket() {
        
        $data = array(
        'name' => $this->name, 
        'email' => $this->email,
        'subject' => $this->subject, 
        'message' => $this->message, 
        'ip' => $_SERVER['REMOTE_ADDR'], 
        'topicId' => $this->topicId,
        'slaId' => $this->slaId
        );
        
        $ticket = $this->_requestApi($data);
        
        if ($ticket['code'] != 201)
            throw new BaseException($ticket['data']);
        
        return $ticket['data'];
    }

    function _requestApi($data) {

        $config = load_config_one('app', array('url', 'path'));

        $osTicketsApiHeaders = array('Expect:', 'X-API-Key: ' . $config['ostickets_api_key']);
        
        $this->core->http->post($config['ostickets_url'], $data, $osTicketsApiHeaders, TRUE);
        
        $response['data'] = $this->core->http->response->data;
        $response['code'] = $this->core->http->response->code;
        
        return $response;
    }

}
