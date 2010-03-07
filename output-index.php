<!DOCTYPE html>
<title>Feed Mashup</title>
<h1>Mashup Feedsets</h1>
<ul>
<?php

while (list($feedset, $feeds) = each($feedsets)) {
	$feedset_uri = $feed_uri . '?feedset=' . $feedset;
    echo '<li>';
		if (isset($feeds['title'])) echo '<h2>'.$feeds['title'].'</h2>';
		if (isset($feeds['sub_title'])) echo '<h3>'.$feeds['sub_title'].'</h3>';
		echo '<a href="' . $feedset_uri . '">' . $feedset . '</a> (<a href="'.$folder_uri.'output-html.php?feed=' . $feedset_uri . '">view as HTML</a>)';
		echo '<ul>';
		/*foreach($feeds as $key => $feed) {
			echo '<li>' . $feed;
			if ($key == 'feeds') {
				foreach ($feed as $feed) {
					echo ' (<a href="'.$feed.'">original</a>, <a href="http://frenzie.dlinkddns.com/simplepie/mashup/output-html.php?feed=' . $feed . '">HTML</a>)';
				}
			}
			echo'</li>';
		}*/
		foreach ($feeds['feeds'] as $feed) {
			echo '<li>' . $feed;
			echo ' (<a href="'.$feed.'">original</a>, <a href="http://frenzie.dlinkddns.com/simplepie/mashup/output-html.php?feed=' . $feed . '">HTML</a>)';
			echo'</li>';
		}
		echo '</ul></li>';
}

?>
</ul>