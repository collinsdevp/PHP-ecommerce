<?php 

// Require the configuration file before any PHP code:
require_once ('config.inc.php');

// Check for a category ID in the URL:
$category = NULL;
if (isset($_GET['cid'])) {

// Typecast it to an integer:
$cid = (int) $_GET['cid'];
// An invalid $_GET['cid'] value would
// be typecast to 0.


 // Include the header file:
include_once ('header.inc.php');
 
 
// $cid must have a valid value.
if ($cid > 0) {

// Get the information from the database
// for this category:
$q = "SELECT category, description FROM categories WHERE category_id='$cid'";
$r = $dbc->query($q);

// Fetch the information:
if ($r->num_rows == 1) {
list ($category, $description) = $r->fetch_array();
 }// End of mysqli_num_rows() IF.

} // End of ($cid > 0) IF.

} // End of isset($_GET['cid']) IF.

 // Use the category as the page title:
if ($category) {
$page_title = $category;
 }


if ($category) { // Show the products.

echo "<h1>$category</h1>\n";

 // Print the category description, if it's not empty.
if (!empty($description)) {
 echo "<p>$description</p>\n";
 }
// Get the widgets in this category:
$q = "SELECT gw_id, name, default_price, description FROM general_widgets WHERE
category_id=$cid";
 $r = $dbc->query($q);

if ($r->num_rows > 1) {
 // Print each:
while (list($gw_id, $wname, $wprice, $wdescription) = $r->fetch_array())
{

// Link to the product.php page:
echo "<h2><a href=\"product.php?gw_id=$gw_id\">$wname</a></h2><p>$wdescription<br
/>\$$wprice</p>\n";

} // End of while loop.

} else { // No widgets here!
echo '<p class="error">There are no widgets in this category.</p>';
 }

} else { // Invalid $_GET['cid']!
echo '<p class="error" style=\'color:brown; font-size:20px;\'> OOPZ !!! : This page has been accessed in error. Try another browser</p>';
}

// Include the footer file to complete the template:
include_once ('footer.inc.php');

 ?>
