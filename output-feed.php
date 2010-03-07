<?php
// Include SimplePie XHTML
require_once('simplepie-xhtml.php');

// Initialize some feeds for use.
$feed = new SimplePie_XHTML();
$feedset = $feedsets[$feedset];

// Feeds to be mashed together.
$feed->set_feed_url($feedset['feeds']);

$feed->set_stupidly_fast ( true ); // Remove or set to false if you don't trust the input.

// Initialize the feed.
$feed->init();

// Make sure the page is being served with the UTF-8 headers.
//$feed->handle_content_type();

header('Content-Type: application/atom+xml;charset=utf-8');
header('Vary: Accept');
?>
<?php if ($feed->error): ?>
		<p><?=$feed->error()?></p>
<?php endif ?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en">
 <title><?php if (isset($feedset['title'])) echo $feedset['title']; else echo $feed_title; ?></title>
 <subtitle><?php if (isset($feedset['sub_title'])) echo $feedset['sub_title']; else echo $feed_sub_title; ?></subtitle>
 <author><name>Frans</name><uri>http://frans.lowter.us/</uri></author>
 <link rel="self" type="application/atom+xml" href="<?php echo $feed_uri; ?>"/>
 <id><?php echo $feed_uri; ?></id>
<?php if (isset($feed_icon)) { ?>
 <icon><?php echo $feed_icon; ?></icon>
<?php } ?>
 <updated><?php echo $feed->get_item()->get_date('Y-m-d\TH:i:sP');?></updated>
<?php
	// Let's loop through each item in the feed.
	foreach($feed->get_items() as $item):

	// Let's give ourselves a reference to the parent $feed object for this particular item.
	$feed = $item->get_feed();
	
	if (isset($feedset['filters']))
		$display = $feed->filter_entry($item, $feedset['filters']);
	else
		$display = true;
	if ($display) {
?>
 <entry>
<?php if ( $item->get_author() ) { ?>
	<author>
		<name><?php echo $item->get_author()->get_name(); ?></name>
	</author>
<?php } ?>
	<title type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="ltr"><?php echo strip_tags($item->get_title()); ?></div></title>
	<link rel="alternate" type="text/html" href="<?php echo $item->get_permalink(); ?>"/>
<!--	<summary></summary>-->
	<content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml">
	<?php //echo $item->get_content(); ?>
	<?php echo $feed->fix_xhtml($item->get_content()); ?>
	</div></content>
	<published><?php echo $item->get_date('Y-m-d\TH:i:sP'); ?></published>
	<updated><?php echo $item->get_date('Y-m-d\TH:i:sP'); ?></updated>
	<id><?php echo $item->get_permalink(); ?></id>
 </entry>
<?php } ?>
<?php endforeach ?>
</feed>