<?php
include_once '../../02_Actions/GlobalVariables.php';
require_once '../../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT']);
$client->addScope('email');
$client->addScope('profile');

header('Location: ' . $client->createAuthUrl());
exit();
?>