<?php
header('Content-type: text/xml');
require_once '../../vendor/autoload.php';
require_once 'class.php';

$server = new \Zend\Soap\Server('http://titomiguelcosta.local/soap/wsdl.php');
$server->setClass('\TitoMiguelCosta\SOAP\Blog');
$server->handle();
