<?php
include_once 'conf.php';

// Setting KEYS
\Codebird\Codebird::setConsumerKey ( TW_API_KEY, TW_API_SECRET );
$cb = \Codebird\Codebird::getInstance ();
$cb->setToken ( TW_TOKEN, TW_TOKEN_SECRET );

// Home Timeline (tweets from people he follow)
$reply = $cb->statuses_homeTimeline ();

/*
 * $reply -> anArray of anObject
 */

foreach ( $reply as $tweet ) 
{
	
	if (isset($tweet->created_at))
	{
		// It's a valid tweet
		echo "--------------------------------" . "\n";
		echo "Creado el:" . ($tweet->created_at) . "\n";
		echo "Texto:" . ($tweet->text) . "\n";
		echo "Usuario:" . ($tweet->user->name) . "\n";
	}
}









