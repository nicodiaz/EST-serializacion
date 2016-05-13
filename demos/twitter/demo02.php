<?php
/**
 * Recupero del timeline del usuario y se graba en la base de datos. 
 * Opcionalmente, se puede reconstruir los objetos deserilizandolos. 
 */

include_once 'conf.php';
include_once 'DB.php';


$db = new DB($settingsDB);

$url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
// $getfield = '?username=nicoclases';
$getfield = '';
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);

$reply = $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

// Cuando se decodifica, reply es un array de objetos.
$tweets = json_decode($reply);

foreach ($tweets as $tweet) 
{
    $db->saveTweet($tweet);
}

// Se Recuperan (deserializan) los tweets de la base
// $resultset = $db->getTweets();

// foreach ($resultset as $result) 
// {
//     print_r($result);
// }


