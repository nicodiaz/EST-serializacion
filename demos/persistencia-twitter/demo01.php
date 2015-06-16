<?php

include_once 'conf.php';

// Setting KEYS
\Codebird\Codebird::setConsumerKey(TW_API_KEY, TW_API_SECRET);

$cb = \Codebird\Codebird::getInstance();

$cb->setToken(TW_TOKEN, TW_TOKEN_SECRET);


$reply = (array) $cb->statuses_homeTimeline();
print_r($reply);




















