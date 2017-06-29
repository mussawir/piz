<?php
//start session in all pages
if (session_status() == PHP_SESSION_NONE) { session_start(); } //PHP >= 5.4.0
//if(session_id() == '') { session_start(); } //uncomment this line if PHP < 5.4.0 and comment out line above

$PayPalMode 			= 'sandbox'; // sandbox or live
$PayPalApiUsername 		= 'musavir_api1.onepointsoftware.com'; //PayPal API Username
$PayPalApiPassword 		= 'STSAPDDGUPZ2Y7EN'; //Paypal API password
$PayPalApiSignature 	= 'AEs5OSrH8h3L1qnz5xcvKwOAo9gcA1UMz8yrnNQKLXLHfGZYp4uDoHtH'; //Paypal API Signature

$PayPalCurrencyCode 	= 'MYR'; //Paypal Currency Code
$PayPalReturnURL 		= SYSTEM_PATH.'/paypal/process.php'; //Point to process.php page
$PayPalCancelURL 		= SYSTEM_PATH.'/index/payment-cancelled'; //Cancel URL if user clicks cancel
?>