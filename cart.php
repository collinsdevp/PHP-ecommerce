<?php 

// Require the configuration file before any PHP code:
require_once ('config.inc.php');

// Include the header file:
$page_title = 'Shopping Cart';
include_once ('header.inc.php');

echo '<h1>View Your Shopping Cart</h1>';

// This page will either add to or update the
// shopping cart, based upon the value of $_REQUEST['do'];
if (isset($_REQUEST['do']) && ($_REQUEST['do'] == 'add') ) { // Add new item.
if (isset($_GET['sw_id'])) { // Check for a product ID.

// Typecast to an integer:
$sw_id = (int) $_GET['sw_id'];

// If it's a positive integer,
// get the item information:
if ($sw_id > 0) {
// Define and execute the query:
 $q = "SELECT name, color, size FROM general_widgets LEFT JOIN specific_widgets USING
(gw_id) LEFT JOIN colors USING (color_id) LEFT JOIN sizes USING (size_id) WHERE sw_id=$sw_id";
$r = $dbc->query($q);


if ($r->num_rows == 1) {

// Get the information:
list ($name, $color, $size) = $r->fetch_array();

// If the cart already contains
// one of these widgets, increment the quantity:
if (isset($_SESSION['cart'][$sw_id])) {

//$_SESSION['cart'][$sw_id]++;

// Display a message:
echo "<p>Another copy of '$name' in color $color, size $size has been added to
your shopping cart.</p>\n";

} else { // New to the cart.

// Add to the cart.
$_SESSION['cart'][$sw_id] = 1;

// Display a message:
echo "<p>The widget '$name' in color $color, size $size has been added to your
shopping cart.</p>\n";

}

} // End of mysqli_num_rows() IF.

} // End of ($sw_id > 0) IF.

} // End of isset($_GET['sw_id']) IF.

} elseif (isset($_REQUEST['do']) && ($_REQUEST['do'] == 'update')) {

// Change any quantities...
// $k is the product ID.
// $v is the new quantity.
foreach ($_POST['qty'] as $k => $v) {

// Must be integers!
$pid = (int) $k;
$qty = (int) $v;

if ($qty == 0) { // Delete item.
unset ($_SESSION['cart'][$pid]);
} elseif ($qty > 0) { // Change quantity.
$_SESSION['cart'][$pid] = $qty;
}


} // End of FOREACH.

 // Print a message.
echo '<p>Your shopping cart has been updated.</p>';

} // End of $_REQUEST IF-ELSE.

// Show the shopping cart if it's not empty:
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

// Retrieve all of the information for the products in the cart:
$q = "SELECT sw_id, name, color, size, default_price, price FROM general_widgets LEFT JOIN
specific_widgets USING (gw_id) LEFT JOIN colors USING (color_id) LEFT JOIN sizes USING (size_id)
WHERE sw_id IN (";

// Add each product ID.
foreach ($_SESSION['cart'] as $sw_id => $v) {
$q .= (int) $sw_id . ',';
 }
$q = substr ($q, 0, -1) . ') ORDER BY name, size, color';
$r = $dbc->query ($q);

if ($r->num_rows > 0) {

// Create a table and a form:
echo '<table border="0" width="60%" cellspacing="2" cellpadding="2" align="center">
<tr>
<td align="left" width="20%"><b>Widget</b></td>
<td align="left" width="15%"><b>Size</b></td>
<td align="left" width="15%"><b>Color</b></td>
<td align="right" width="15%"><b>Price</b></td>
<td align="center" width="10%"><b>Qty</b></td>
<td align="right" width="15%"><b>Total Price</b></td>
</tr>
<form action="cart.php" method="post">
<input type="hidden" name="do" value="update" />
';

// Print each item:
$total = 0; // Total cost of the order.
while ($row = $r->fetch_array ()) {

// Determine the price:
$price = (empty($row['price'])) ? $row['default_price'] : $row['price'];

// Calculate the total and sub-totals:
$subtotal = $_SESSION['cart'][$row['sw_id']] * $price;
$total += $subtotal;
$subtotal = number_format($subtotal, 2);


// Print the row:
echo <<<EOT
<tr>
<td align="left">{$row['name']}</td>
<td align="left">{$row['size']}</td>
<td align="left">{$row['color']}</td>
<td align="right">\$$price</td>
<td align="center"><input type="text" size="3" name="qty[{$row['sw_id']}]"
value="{$_SESSION['cart'][$row['sw_id']]}" /></td>
<td align="right">\$$subtotal</td>
</tr>\n
EOT;

} // End of the WHILE loop.
// Print the footer, close the table, and the form:
echo ' <tr>
<td colspan="5" align="right"><b>Total:<b></td>
<td align="right">$' . number_format ($total, 2) . '</td>
</tr>
<tr>
<td colspan="6" align="center">Set an item\'s quantity to 0 to remove it from your
cart.</td>
</tr>
</table><div align="center"><button type="submit" name="submit" value="update">Update
Cart</button> &nbsp; &nbsp; &nbsp; &nbsp;
<a href="checkout.php"><button type="button" name="checkout"
value="Checkout">Checkout</button></a></div>
</form>';

} // End of mysqli_num_rows() IF.

 } else {
 echo '<p>Your cart is currently empty.</p>';
}

 // Include the footer file to complete the template:
 include_once ('footer.inc.php');

 ?>
