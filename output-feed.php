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

// Set the correct header for an atom feed.
header('Content-Type: application/atom+xml;charset=utf-8');
header('Vary: Accept');
?>
<?php if ($feed->error): ?>
 <p><?=$feed->error()?></p>
<?php endif ?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en">
 <title><?php if (isset($feedset['title'])) echo $feedset['title']; else echo $feed_title; ?></title>
 <subtitle><?php if (isset($feedset['sub_title'])) echo $feedset['sub_title']; else echo $feed_sub_title; ?></subtitle>
 <author>
  <name><?php if (isset($feedset['author_name'])) echo $feedset['author_name']; else echo $feed_author_name; ?></name>
<?php if ( isset($feedset['author_url']) || isset($feed_author_url) ) { ?>
	<uri><?php if (isset($feedset['author_url'])) echo $feedset; else echo $feed_author_url; ?></uri>
<?php } ?>
 </author>
 <link rel="self" type="application/atom+xml" href="<?php echo $feed_uri; ?>"/>
 <id><?php echo $feed_uri; ?></id>
<?php if (isset($feedset['feed_icon'])) { ?>
 <icon><?php echo $feedset['feed_icon']; ?></icon>
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
	<title><?php echo strip_tags($item->get_title()); ?></title>
<!--	<summary></summary>-->
	<content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml">
	<?php echo $feed->fix_xhtml($item->get_content()); ?>
	</div></content>
	<published><?php echo $item->get_date('Y-m-d\TH:i:sP'); ?></published>
	<updated><?php echo $item->get_date('Y-m-d\TH:i:sP'); ?></updated>
	<id><?php echo $item->get_permalink(); ?></id>
<?php
if ($enclosure = $item->get_enclosure()) {
	$enc_output = '	<link rel="enclosure" ';
	$enc_output .= 'type="'.$enclosure->get_type().'" ';
	if ( $enclosure->get_title() ) $enc_output .= 'title='.$enclosure->get_title().'" ';
	$enc_output .= 'href="'.$enclosure->get_link().'" ';
	if ($enclosure->get_length() != 0)
		$enc_output .= 'length="'.$enclosure->get_length().'" ';
	else {
		$ch = curl_init($enclosure->get_link());
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //not necessary unless the file redirects (like the PHP example we're using here)
		$data = curl_exec($ch);
		curl_close($ch);
		if ($data === false) {
			echo 'cURL failed';
			exit;
		}
		$contentLength = 0;
		if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
			$contentLength = (int)$matches[1];
		}
		$enc_output .= 'length="'.$contentLength.'" ';
	}
	$enc_output .= '/>';
	echo $enc_output;
}
else { ?>
	<link rel="alternate" type="text/html" href="<?php echo $item->get_permalink(); ?>"/>
<?php } ?>
 </entry>
<?php } ?>
<?php endforeach ?>
</feed>