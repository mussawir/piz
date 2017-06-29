<?php

	/*==================================================================

	 PayPal Express Checkout Call

	 ===================================================================

	*/

require_once ("paypalfunctions.php");
include 'config.php'; 
$PaymentOption = "PayPal";

if ( $PaymentOption == "PayPal" )

{

	/*

	'------------------------------------

	' The paymentAmount is the total value of 

	' the shopping cart, that was set 

	' earlier in a session variable 

	' by the shopping cart page

	'------------------------------------

	*/

	

	$finalPaymentAmount =  $_SESSION["Payment_Amount"];

	

	/*

	'------------------------------------

	' Calls the DoExpressCheckoutPayment API call

	'

	' The ConfirmPayment function is defined in the file PayPalFunctions.jsp,

	' that is included at the top of this file.

	'-------------------------------------------------

	*/



	$resArray = ConfirmPayment($finalPaymentAmount);

	$ack = strtoupper($resArray["ACK"]);

	if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )

	{

		/*

		'********************************************************************************************************************

		'

		' THE PARTNER SHOULD SAVE THE KEY TRANSACTION RELATED INFORMATION LIKE 

		'                    transactionId & orderTime 

		'  IN THEIR OWN  DATABASE

		' AND THE REST OF THE INFORMATION CAN BE USED TO UNDERSTAND THE STATUS OF THE PAYMENT 

		'

		'********************************************************************************************************************

		*/


		$transactionId		= $resArray["TRANSACTIONID"]; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 

		$transactionType 	= $resArray["TRANSACTIONTYPE"]; //' The type of transaction Possible values: l  cart l  express-checkout 

		$paymentType		= $resArray["PAYMENTTYPE"];  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 

		$orderTime 			= $resArray["ORDERTIME"];  //' Time/date stamp of payment

		$amt				= $resArray["AMT"];  //' The final amount charged, including any shipping and taxes from your Merchant Profile.

		$currencyCode		= $resArray["CURRENCYCODE"];  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 

		$feeAmt				= $resArray["FEEAMT"];  //' PayPal fee amount charged for the transaction

		$settleAmt			= $resArray["SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.

		$taxAmt				= $resArray["TAXAMT"];  //' Tax charged on the transaction.

		$exchangeRate		= $resArray["EXCHANGERATE"];  //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer’s account.

		

		/*

		' Status of the payment: 

				'Completed: The payment has been completed, and the funds have been added successfully to your account balance.

				'Pending: The payment is pending. See the PendingReason element for more information. 

		*/

		

		$paymentStatus	= $resArray["PAYMENTSTATUS"]; 



		/*

		'The reason the payment is pending:

		'  none: No pending reason 

		'  address: The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile. 

		'  echeck: The payment is pending because it was made by an eCheck that has not yet cleared. 

		'  intl: The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview. 		

		'  multi-currency: You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment. 

		'  verify: The payment is pending because you are not yet verified. You must verify your account before you can accept this payment. 

		'  other: The payment is pending for a reason other than those listed above. For more information, contact PayPal customer service. 

		*/

		

		$pendingReason	= $resArray["PENDINGREASON"];  



		/*

		'The reason for a reversal if TransactionType is reversal:

		'  none: No reason code 

		'  chargeback: A reversal has occurred on this transaction due to a chargeback by your customer. 

		'  guarantee: A reversal has occurred on this transaction due to your customer triggering a money-back guarantee. 

		'  buyer-complaint: A reversal has occurred on this transaction due to a complaint about the transaction from your customer. 

		'  refund: A reversal has occurred on this transaction because you have given the customer a refund. 

		'  other: A reversal has occurred on this transaction due to a reason not listed above. 

		*/

		

		$reasonCode		= $resArray["REASONCODE"]; 
		//bring data here...
        $email 				= $_POST["email"]; // ' Email address of payer.
		$payer_id 			= $_POST["payerid"];// ' Unique PayPal customer account identification number.

		$payer_status		= $_POST["payer_status"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.

		$salutation			= $_POST["salutation"];// ' Payer's salutation.

		$first_name			= $_POST["first_name"]; // ' Payer's first name.

		$middle_name			= $_POST["middle_name"];// ' Payer's middle name.

		$last_name			= $_POST["last_name"];// ' Payer's last name.

		$suffix				= $_POST["suffix"]; // ' Payer's suffix.

		$country_code			= $_POST["country_code"]; // ' Payer's country of residence in the form of ISO standard 3166 two-character country codes.

		$business			= $_POST["business"]; // ' Payer's business name.

		$ship_to_name		= $_POST["ship_to_name"]; // ' Person's name associated with this address.

		$ship_to_street		= $_POST["ship_to_street"]; // ' First street address.

		$ship_to_street2		= $_POST["ship_to_street2"]; // ' Second street address.

		$ship_to_city			= $_POST["ship_to_city"]; // ' Name of city.

		$ship_to_state		= $_POST["ship_to_state"]; // ' State or province

		$ship_to_country_code	= $_POST["ship_to_country_code"]; // ' Country code. 

		$ship_to_zip			= $_POST["ship_to_zip"]; // ' U.S. Zip code or other country-specific postal code.

		$address_status 		= $_POST["address_status"]; // ' Status of street address on file with PayPal   

		$invoice_number		= $_POST["invoice_number"]; // ' Your own invoice or tracking number, as set by you in the element of the same name in SetExpressCheckout request .

		$phone_number			= $_POST["phone_number"]; // ' Payer's contact telephone number. Note:  PayPal returns a contact telephone number only if your Merchant account profile settings require that the buyer enter one. 

/*$paid_amount = $_POST["paid_amount"];
 echo $paid_amount;
   echo $email; echo "<br/>";
   echo $salutation; echo "<br/>";
   echo $salutation;echo "<br/>";
   echo $first_name;echo "<br/>";
   echo $last_name;echo "<br/>";
   echo $country_code;echo "<br/>";
    echo $ship_to_name;echo "<br/>";
	echo $ship_to_street;echo "<br/>";
	$ship_to_street2; echo "<br/>";
	$ship_to_city;echo "<br/>";
	$ship_to_state;echo "<br/>";
	 echo $ship_to_zip;echo "<br/>";
	 echo $ship_to_country_code;echo "<br/>";
echo $phone_number;*/
    header('Location: http://netefct.com/dev/index/payment-completed/1/'.$email . '/2/'.$first_name . '/3/'.$last_name);  

	}

	else  

	{

		//Display a user friendly Error on the page using any of the following error information returned by PayPal

		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);

		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);

		$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);

		$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

		

		echo "GetExpressCheckoutDetails API call failed. ";

		echo "Detailed Error Message: " . $ErrorLongMsg;

		echo "Short Error Message: " . $ErrorShortMsg;

		echo "Error Code: " . $ErrorCode;

		echo "Error Severity Code: " . $ErrorSeverityCode;

	}

}		

		

?>