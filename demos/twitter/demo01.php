<?php
/**
 * Ejemplo de recupero del timeline del usuario, mostrandolo por salida standard, 
 * pero utilizando las propiedades de los objetos decodificados.
 */

include_once 'conf.php';

$url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
// $getfield = '?username=nicoclases';
$getfield = '';
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);

$reply = $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

// Cuando se decofidica, reply es un array de objetos.
$tweets = json_decode($reply);

foreach ($tweets as $tweet) 
{
	echo "--------------------------------" . "\n";
	echo "Creado el:" . ($tweet->created_at) . "\n";
	echo "Texto:" . ($tweet->text) . "\n";
	echo "Usuario:" . ($tweet->user->name) . "\n";
	echo "URL Imagen:" . ($tweet->user->profile_image_url) . "\n";
	echo "\n";
}


