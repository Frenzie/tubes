<!DOCTYPE html>
<title>Tubes Mashup Feedsets</title>
<style>
body{margin:0 auto;width:90%;}
</style>
<h1>Tubes Mashup Feedsets</h1>
<?php

while (list($feedset, $feeds) = each($feedsets)) {
	$feedset_uri = $feed_uri . '?feedset=' . $feedset;
		if (isset($feeds['title'])) echo '<h2>'.$feeds['title'].'</h2>';
		if (isset($feeds['sub_title'])) echo '<h3>'.$feeds['sub_title'].'</h3>';
		echo '<a href="' . $feedset_uri . '">' . $feedset . '</a> (<a href="'.$folder_uri.'output-html.php?feed=' . $feedset_uri . '">view as HTML</a>)';
		echo '<ul>';
		foreach ($feeds['feeds'] as $feed) {
			echo '<li>' . $feed;
			echo ' (<a href="'.$feed.'">original</a>, <a href="http://frenzie.dlinkddns.com/simplepie/mashup/output-html.php?feed=' . $feed . '">HTML</a>)';
			echo'</li>';
		}
		echo '</ul>';
}

?>