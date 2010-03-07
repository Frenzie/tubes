<?php
// SimplePie-based feed mashup
// http://frans.lowter.us/2010/02/14/simplepie-based-feed-mashup/
// Spread, use, modify, etc. as much as you like. A link back or a comment would be appreciated, but there's no need to. -Frans

// include configuration
require_once('config.php');

// Get feedset from URI, if applicable.
if ( isset($_GET['feedset']) ) $feedset = $_GET['feedset'];


if ( isset($feedset) )
	include 'output-feed.php';
else
	include 'output-index.php';
?>