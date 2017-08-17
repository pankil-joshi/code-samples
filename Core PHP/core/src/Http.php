<?php

namespace Quill;

class Http {
    
    public $response;

    function __construct() {
        
        $this->response = (new \stdClass());
    }

    private function _initCurl() {

        return curl_init();
    }

    private function _closeCurl($ch) {

        return curl_close($ch);
    }

    public function get($url) {

        $ch = $this->_initCurl();
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $this->_closeCurl($ch);

        return $response;
    }

    public function post($url, $params, $headers = array(), $json = FALSE, $auth = array()) {

        $ch = $this->_initCurl();

        if ($json) {

            $params = json_encode($params);
        }
        
        if (!empty($auth))
            curl_setopt($ch, CURLOPT_USERPWD, $auth['username'] . ":" . $auth['password']);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $this->response->data = $response = curl_exec($ch);
        $this->response->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        $this->_closeCurl($ch);

        return $response;
    }

    public function getFileSize($url) {

        $result = -1;
        $ch = $this->_initCurl();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, get_user_agent_string());
        $response = curl_exec($ch);
        $this->_closeCurl($ch);
        if ($response) {
            $content_length = "unknown";
            $status = "unknown";
            if (preg_match("/^HTTP\/1\.[01] (\d\d\d)/", $response, $matches)) {
                $status = (int) $matches[1];
            }
            if (preg_match("/Content-Length: (\d+)/", $response, $matches)) {
                $content_length = (int) $matches[1];
            }
            // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
            if ($status == 200 || ($status > 300 && $status <= 308)) {
                $result = $content_length;
            }
        }

        return $result;
    }

    public function createCurlFile($filename, $contentType, $postname) {

        if (function_exists('curl_file_create')) {
            return curl_file_create($filename, $contentType, $postname);
        }
        
        $value = "@{$filename};filename=" . $postname;

        if ($contentType) {
            $value .= ';type=' . $contentType;
        }

        return $value;
    }

}
