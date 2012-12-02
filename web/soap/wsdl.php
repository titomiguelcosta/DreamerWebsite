<?php
//header('Content-type: text/xml');
require_once '../../vendor/autoload.php';
require_once 'class.php';

// set up WSDL auto-discovery
$wsdl = new \Zend\Soap\AutoDiscover();

// attach SOAP service class
$wsdl->setClass('\TitoMiguelCosta\SOAP\Blog');

// set SOAP action URI
//$wsdl->setUri('http://titomiguelcosta.local/soap/server.php');
$wsdl->setUri('http://titomiguelcosta.local/soap/wsdl.xml');

// handle request
echo $wsdl->toXML();
