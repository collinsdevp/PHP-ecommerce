<?php 

 error_reporting(0);
/*
* This is a bare-bones checkout page.
* For demonstration purposes, this page only
* takes and validates the credit card information.
* The assumption is that other information--
* name, address, etc.
* --would be retrieved from the database after logging in
* and also confirmed on this page.
*/

 // Require the configuration file before any PHP code:
 require_once ('config.inc.php');

// Include the header file:
$page_title = 'Checkout';
include_once ('header.inc.php');

echo '<h1>Checkout</h1>';

// Set the time zone:
date_default_timezone_set('GMT');

// Check for form submission.
if (isset($_POST['submitted'])) {

// Validate the credit card...

// Check the expiration date:
$year = (int) $_POST['cc_exp_year'];



$month = (int) $_POST['cc_exp_month'];

 // Get the current date:
$today = getdate();

// Validate the expiration date:
if ( ($year > $today['year']) OR
( ($year == $today['year']) AND ($month >= $today['mon']) )
) {

// Include the class definition:
require ('CreditCard.php');

// Create the object:
$cc = new Validate_Finance_CreditCard();

//$cc = new Validate_Finance_CreditCard();

// Validate the card number and type:
if ($cc->number($_POST['cc_number'], $_POST['cc_type'])) {

// Use XXX to process the order!!!
// If payment goes through, complete the order!
echo '<p>Your order is complete (but not really).</p>';
include_once ('footer.inc.php');
exit();

} else { // Invalid card number or type.
echo '<p class="error">Please enter a valid credit card number and type.</p>';
}

} else { // Invalid date.
echo '<p class="error">Please enter a valid expiration date.</p>';
}

}

// Show the form.
?>
<form action="checkout.php" method="post">
<input type="hidden" name="submitted" value="true" />
<table border="0" width="30%" cellspacing="2" cellpadding="2" align="center">
<tr>
<td align="right">Credit Card Type:</td>
<td align="left"><select name="cc_type">
<!--<option value="amex">American Express</option>-->
<option value="visa">Visa</option>
<option value="mastercard">MasterCard</option>

<option value="diners club">Diners Club</option>
<option value="enroute">enRoute</option>
</select></td>
</tr>
<tr>
<td align="right">Credit Card Number:</td>
<td align="left"><input type="text" name="cc_number" maxlength="20" /></td>
</tr>
<tr>
<td align="right">Expiration Date:</td>
<td align="left"><select name="cc_exp_month">
<option value="">Month</option>
<option value="1">Jan</option>
<option value="2">Feb</option>
<option value="3">Mar</option>
<option value="4">Apr</option>
<option value="5">May</option>
<option value="6">Jun</option>
<option value="7">Jul</option>
<option value="8">Aug</option>
<option value="9">Sep</option>
<option value="10">Oct</option>
<option value="11">Nov</option>
<option value="12">Dec</option>
</select> <select name="cc_exp_year">
<option value="">Year</option>
<?php for ($start = date('Y'), $end = date('Y') + 10; $start < $end; $start++) {
echo "<option value=\"$start\">$start</option>\n";
}
?>
</select></td>
</tr>

<tr>
<td align="center" colspan="2"><button type="submit" name="submit"
value="update">Checkout</button></td>
</tr>
</table>
</form>

<?php
// Include the footer file to complete the template:
include_once ('footer.inc.php');

?>
