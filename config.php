<?php
// SimplePie-based feed mashup
// http://frans.lowter.us/2010/02/14/simplepie-based-feed-mashup/
// Spread, use, modify, etc. as much as you like. A link back or a comment would be appreciated, but there's no need to. -Frans

// set a few variables
//$feed_uri = 'http://somewhere/mashup/'; // Permanent URI for feed, so basically just a reference to where this script is located. Required.
$folder_uri = 'http://' . $_SERVER['SERVER_NAME'] . substr($_SERVER['PHP_SELF'], 0, -9); // Strips index.php
$feed_uri = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; // URI of script for inclusion in output feed.
if ( isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '' ) $feed_uri .= '?' . $_SERVER['QUERY_STRING'];
$feed_title = 'Feed Mashup'; // Default title, used when nothing specified in set.
$feed_sub_title = 'Multiple Feeds Condensed Into One Feed'; // Default subtitle, used when nothing specified in set.
$feed_author_name = 'Tubes'; // Required.
$feed_author_url = 'http://frans.lowter.us/'; // Optional.

// set the feed URIs
$feedsets = array(
	'atheism' => array(
		'title' => 'Atheist Mashup',
		'sub_title' => 'Heathen Feeds United',
		'feeds' => array(
			'http://feeds.feedburner.com/godvoordommen',
			'http://feeds.feedburner.com/HeavingDeadCats',
		),
	),
	'comics' => array(
		'title' => 'Comics Mashup',
		'sub_title' => 'Webcomics Amassed',
		'feeds' => array(
			'http://www.jaynaylor.com/betterdays/atom.xml',
			'http://www.cad-comic.com/rss/rss.xml',
			'http://www.thedevilspanties.com/comic.rss',
			'http://users.livejournal.com/_gertrude_/data/atom',
			'http://www.stonemakerargument.com/feed.xml',
			'http://www.questionablecontent.net/QCRSS.xml',
			'http://feeds.feedburner.com/wulffmorgenthaler',
			'http://www.xkcd.com/atom.xml',
		),
	),
	'language' => array(
		'title' => 'Language Mashing',
		'sub_title' => 'This Is No Gerund',
		'feeds' => array(
			'http://arnoldzwicky.wordpress.com/feed/atom/',
			'http://languagelog.ldc.upenn.edu/nll/?feed=atom',
			'http://feeds.feedburner.com/TenserSaidTheTensor',
		),
	),
	'news' => array(
		'title' => 'UN News',
		'feeds' => array(
			'http://www.unmultimedia.org/radio/english/rss/itunes.xml',
		),
	),
	'nieuwslijn' => array(
		'title' => 'Nieuwslijn Once a Day',
		'sub_title' => 'Who Wants The News Six Times a Day?',
		'filters' => array(
			array('permit', 'title', '07:00:00'),
			array('permit', 'title', '08:00:00'),
		),
		'feeds' => array(
			'http://download.omroep.nl/rnw/smac/podcast/xml/nl_nieuwslijn.xml',
		),
	),
	'opera' => array(
		'title' => 'Opera Feeds',
		'sub_title' => 'Opera News Condensed',
		'feed_icon' => 'http://static.myopera.com/favicon.ico',
		'feeds' => array(
			'http://my.opera.com/core/xml/atom/blog/',
			'http://my.opera.com/desktopteam/xml/atom/blog/',
			'http://my.opera.com/devblog/xml/atom/blog/',
			'http://my.opera.com/dragonfly/xml/atom/blog/',
			'http://my.opera.com/haavard/xml/atom/blog/',
			'http://my.opera.com/hallvors/xml/atom/blog/',
			'http://my.opera.com/operaqa/xml/atom/blog/',
			'http://my.opera.com/ruario/xml/atom/blog/',
			'http://my.opera.com/securitygroup/xml/atom/blog/',
			'http://my.opera.com/sitepatching/xml/atom/blog/',
			'http://feeds.feedburner.com/OperaWatch',
		),
	),
	'scifi' => array(
		'title' => 'SciFi Mashup',
		'sub_title' => 'From Steampunk to Paleofuture',
		'feeds' => array(
			'http://www.paleofuture.com/blog/atom.xml',
			'http://feeds.feedburner.com/SteampunkWorkshop',
		),
	),
	'webdev' => array(
		'title' => 'Web Dev Ratatouille',
		'sub_title' => 'Important Reading!',
		'feeds' => array(
			'http://www.456bereastreet.com/feed.xml',
			'http://annevankesteren.nl/feeds/weblog',
			'http://www.cssquirrel.com/feed/',
			'http://diveintomark.org/feed/',
			//'http://internetducttape.com/feed/',
			'http://www.quirksmode.org/blog/atom.xml',
			'http://www.search-this.com/feed/',
		),
	),
);
?>