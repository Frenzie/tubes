<?php
// SimplePie-based feed mashup
// http://fransdejonge.com/2010/02/simplepie-based-feed-mashup/
// Spread, use, modify, etc. as much as you like under the terms of the GPLv3 license. A link back or a comment would be appreciated, but there's no need to. -Frans

// include configuration
require_once('config.php');

$feedset = NULL;
$notification = false;

// Get feedset from URI, if applicable.
if ( isset($_GET['feedset']) ) {
	$feedset = $_GET['feedset'];
	if ( isset($_GET['notification']) ) $notification = true;
}

if ( isset($feedset) )
	include 'output-feed.php';
else
	include 'output-index.php';
?>
