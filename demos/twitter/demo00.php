<?php
include_once 'conf.php';

$url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
// $getfield = '?username=nicoclases';
$getfield = '';
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);

$reply = $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

echo "---------- Inicio de Respuesta -----------" . "\n";
echo $reply . "\n";
echo "---------- Fin de Respuesta -----------" . "\n";