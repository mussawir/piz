<?php
include 'config.php'; 
/*==================================================================
 PayPal Express Checkout Call
===================================================================

*/

// Check to see if the Request object contains a variable named 'token'	

$token = "";
if (isset($_REQUEST['token']))
{
	$token = $_REQUEST['token'];
}
// If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.	

if ( $token != "" )
{
	require_once ("paypalfunctions.php");
	/*

	'------------------------------------

	' Calls the GetExpressCheckoutDetails API call

	'

	' The GetShippingDetails function is defined in PayPalFunctions.jsp

	' included at the top of this file.

	'-------------------------------------------------

	*/
	$resArray = GetShippingDetails( $token );

	$ack = strtoupper($resArray["ACK"]);

	if( $ack == "SUCCESS" || $ack == "SUCESSWITHWARNING") 

	{

		/*

		' The information that is returned by the GetExpressCheckoutDetails call should be integrated by the partner into his Order Review 

		' page		

		*/

		$email 				= $resArray["EMAIL"]; // ' Email address of payer.

		$payerid 			= $resArray["PAYERID"]; // ' Unique PayPal customer account identification number.

		$payer_status		= $resArray["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.

		$salutation			= $resArray["SALUTATION"]; // ' Payer's salutation.

		$first_name			= $resArray["FIRSTNAME"]; // ' Payer's first name.

		$middle_name			= $resArray["MIDDLENAME"]; // ' Payer's middle name.

		$last_name			= $resArray["LASTNAME"]; // ' Payer's last name.

		$suffix				= $resArray["SUFFIX"]; // ' Payer's suffix.

		$country_code			= $resArray["COUNTRYCODE"]; // ' Payer's country of residence in the form of ISO standard 3166 two-character country codes.

		$business			= $resArray["BUSINESS"]; // ' Payer's business name.

		$ship_to_name		= $resArray["SHIPTONAME"]; // ' Person's name associated with this address.

		$ship_to_street		= $resArray["SHIPTOSTREET"]; // ' First street address.

		$ship_to_street2		= $resArray["SHIPTOSTREET2"]; // ' Second street address.

		$ship_to_city			= $resArray["SHIPTOCITY"]; // ' Name of city.

		$ship_to_state		= $resArray["SHIPTOSTATE"]; // ' State or province

		$ship_to_country_code	= $resArray["SHIPTOCOUNTRYCODE"]; // ' Country code. 

		$ship_to_zip			= $resArray["SHIPTOZIP"]; // ' U.S. Zip code or other country-specific postal code.

		$address_status 		= $resArray["ADDRESSSTATUS"]; // ' Status of street address on file with PayPal   

		$invoice_number		= $resArray["INVNUM"]; // ' Your own invoice or tracking number, as set by you in the element of the same name in SetExpressCheckout request .

		$phon_number			= $resArray["PHONENUM"]; // ' Payer's contact telephone number. Note:  PayPal returns a contact telephone number only if your Merchant account profile settings require that the buyer enter one. 

                $paid_amount = $resArray["AMT"];

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

<body onload="redirect()">
<?php /*?>  <table>
        <tr><td>Paid Amount:</td> <td><?php echo $paid_amount; ?></td></tr>
        <tr><td>Your email address:</td> <td><?php echo $email; ?></td></tr>
        <tr><td>Salutation:</td> <td><?php echo $salutation; ?></td></tr>
    <tr><td>First name:</td> <td><?php echo $first_name; ?></td></tr>
    <tr><td>Last name:</td> <td><?php echo $last_name; ?></td></tr>
    <tr><td>Country code:</td> <td><?php echo $country_code; ?></td></tr>
    <tr><td>Ship to name:</td> <td><?php echo $ship_to_name; ?></td></tr>
    <tr><td>Street:</td> <td><?php echo $ship_to_street. " " . $ship_to_street2; ?></td></tr>
    <tr><td>City:</td> <td><?php echo $ship_to_city; ?></td></tr>
    <tr><td>State:</td> <td><?php echo $ship_to_state; ?></td></tr>
    <tr><td>Zip code:</td> <td><?php echo $ship_to_zip; ?></td></tr>
    <tr><td>Country Code:</td> <td><?php echo $ship_to_country_code; ?></td></tr>
    <tr><td>Phone:</td> <td><?php echo $phone_number; ?></td></tr>
    </table><?php */?>
        
<form action='order_confirm.php' METHOD='POST' name="frm">
   <input type="hidden" name="paid_amount" value="<?php echo $paid_amount ?>"/>
<input type="hidden" name="email" value="<?php echo $email ?>"/>
<input type="hidden" name="payerid" value="<?php echo $payerid ?>"/>
<input type="hidden" name="payer_status" value="<?php echo $payer_status ?>"/>
<input type="hidden" name="salutation" value="<?php echo $salutation ?>"/>
<input type="hidden" name="first_name" value="<?php echo $first_name ?>"/>
<input type="hidden" name="middle_name" value="<?php echo $middle_name ?>"/>
<input type="hidden" name="last_name" value="<?php echo $last_name ?>"/>
<input type="hidden" name="suffix" value="<?php echo $suffix ?>"/>

<!-- input type="submit" value="Review"/ -->

</form>
<center>
    <h3>Please wait...</h3>
    <img alt=""  src="animation_processing.gif"/>


</center>
</body>

<script type="text/javascript"  language="JavaScript">



function redirect()

{

document.frm.submit();

}

function Func1Delay()

{

setTimeout("redirect()", 500);

}

</script>