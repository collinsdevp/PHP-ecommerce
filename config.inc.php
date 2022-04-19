<?php 


 # ******************** #
 # ***** SETTINGS ***** #

// Errors are emailed here.
$error_log= 'error_log.txt';
$contact_email = 'address@example.com';

// Determine whether we're working on a local server
 // or on the real server:
$REMOTE_ADDR = "127.0.0.1" || "localhost";
if ($REMOTE_ADDR == "127.0.0.1" || $REMOTE_ADDR == "localhost") {
$local = TRUE;
} else {
$local = FALSE;
}

// Determine location of files and the URL of the site:
// Allow for development on different servers.
if ($local) {

// Always debug when running locally:
$debug = TRUE;
// Define the constants:
define ('BASE_URI', '/path/to/html/folder/');
define ('BASE_URL', '/developing E-commerce/');

} else {

define ('BASE_URI', '/path/to/live/html/folder/');

define ('BASE_URL', 'http://www.example.com/');

}

/*
* Most important setting...
* The $debug variable is used to set error management.
* To debug a specific page, do this:

 $debug = TRUE;
 require_once('./includes/config.inc.php');

* on that page.
*
* To debug the entire site, do

$debug = TRUE;

* before this next conditional.
*/

// Assume debugging is off.
if (!isset($debug)) {
$debug = FALSE;
}

# ***** SETTINGS ***** #
# ******************** #

 # **************************** #
 # ***** ERROR MANAGEMENT ***** #

 // Create the error handler.
function my_error_handler ($e_number, $e_message, $e_file, $e_line, $e_vars) {

global $debug, $contact_email;

 // Build the error message.
$message = "An error occurred in script '$e_file' on line $e_line: \n<br />$e_message\n<br
/>";

// Add the date and time.
$message .= "Date/Time: " . date('n-j-Y H:i:s') . "\n<br />";

// Append $e_vars to the $message.
//$message .= "<pre>" . print_r ($e_vars, 1) . "</pre>\n<br />";

if ($debug) { // Show the error.




echo '<p class="error">' . $message . '</p>';
error_log ($message, 3, $error_log);
} else {

// Log the error:
//error_log ($message, 1, $contact_email); // Send email.
// Only print an error message if the error isn't a notice or strict.
if ( ($e_number != E_NOTICE) && ($e_number < 2048)) {
echo '<p class="error">A system error occurred. We apologize for the
inconvenience.</p>';
}

 } // End of $debug IF.

} // End of my_error_handler() definition.

// Use my error handler:
set_error_handler ('my_error_handler');

# ***** ERROR MANAGEMENT ***** #
# **************************** #


 # ************************** #
 # ***** DATABASE STUFF ***** #

// Connect to the database:
$dbc = new mysqli ('localhost', 'root', '', 'ecommerce') OR
trigger_error("Could not connect to the database!\n<br />MySQL Error: " .
mysql_connect_error());

//mysql_select_db('ecommerce');

// Create a function for escaping the data.
function escape_data ($data) {

// Need the connection:
global $dbc;

// Address Magic Quotes.
if (ini_get('magic_quotes_gpc')) {
$data = stripslashes($data);
}

// Trim and escape:
return mysql_real_escape_string($dbc, trim($data));

} // End of escape_data() function.

 # ***** DATABASE STUFF ***** #
 # ************************** #

?>
