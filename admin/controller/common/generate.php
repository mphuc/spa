<?php
require 'vendor/autoload.php';
$authenticator = new PHPGangsta_GoogleAuthenticator();
$secret = $authenticator->createSecret();
echo "Secret: ".$secret."\n";;


$website = 'http://hayageek.com'; //Your Website
$title= 'Hayageek';
$qrCodeUrl = $authenticator->getQRCodeGoogleUrl($title, $secret,$website);
echo "Open this link in browser & scan with Google Authenticator App \n";
echo $qrCodeUrl."\n";

?>
