<?php

$response = new \Quill\Response();

$data = array();

$data['errors']['message'] = $exception->getMessage();

if (method_exists($exception, 'getType') && $_SERVER['HTTP_HOST'] == 'www.tagzie.com' && $exception->getType() == 'database') {

    $data['errors']['message'] = 'Unable to connect to Tagzie, please try again later [Error code : TGZx05';
}
if (method_exists($exception, 'getArray'))
    $data['errors']['array'] = $exception->getArray();
if (method_exists($exception, 'getType'))
    $data['errors']['type'] = $exception->getType();
if (method_exists($exception, 'getMeta'))
    $data['errors']['meta'] = $exception->getMeta();
if ($_SERVER['HTTP_HOST'] != 'www.tagzie.com') {
    $data['errors']['line'] = $exception->getLine();
    $data['errors']['file'] = $exception->getFile();
    $data['errors']['trace'] = $exception->getTrace();
    $data['errors']['code'] = $exception->getCode();
}
$response->json($data, true, array('success' => false, 'code' => (!empty($exception->getCode()))? $exception->getCode() : ''));

header("HTTP/1.1 200");

die();
