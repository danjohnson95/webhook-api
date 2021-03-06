<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!function_exists('getallheaders')){
    function getallheaders(){
    	$headers = '';
     	foreach($_SERVER as $name => $value){
        	if(substr($name, 0, 5) == 'HTTP_'){
            	$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

require __DIR__."/WebhookListener.php";

$Webhook = new WebhookListener();