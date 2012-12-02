<?php

require_once '../../vendor/autoload.php';
require_once 'class.php';

$client1 = new \Zend\Soap\Client('http://titomiguelcosta.local/soap/wsdl.php');

$client2 = new \Zend\Soap\Client('http://titomiguelcosta.local/dev.php/blog/soap/wsdl');

echo strlen(file_get_contents('http://titomiguelcosta.local/soap/wsdl.php'));
echo "---";
echo strlen(file_get_contents('http://titomiguelcosta.local/dev.php/blog/soap/wsdl.php'));
//die();
echo print_r($client2->getPost("aa"), true);