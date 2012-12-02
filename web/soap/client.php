<?php

require_once '../../vendor/autoload.php';
require_once 'class.php';

$client1 = new \Zend\Soap\Client('http://titomiguelcosta.local/soap/wsdl.php');

$client2 = new \Zend\Soap\Client('http://titomiguelcosta.local/blog/soap/wsdl');

//die();
echo print_r($client1->getPost("aa"), true);