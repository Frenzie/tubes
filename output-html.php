<?php
// Start counting time for the page load
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

require_once('config.php');
// Include SimplePie XHTML
require_once(LIB_PATH . '/SimplePie_XHTML.php');

// Create a new instance of the SimplePie object
$feed = new SimplePie_XHTML();

// Make sure that page is getting passed a URL
if (isset($_GET['feed']) && $_GET['feed'] !== '')
{
	// Strip slashes if magic quotes is enabled (which automatically escapes certain characters)
	if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
	{
		$_GET['feed'] = stripslashes($_GET['feed']);
	}
	
	// Use the URL that was passed to the page in SimplePie
	$feed->set_feed_url($_GET['feed']);
	
	// XML dump
	$feed->enable_xml_dump(isset($_GET['xmldump']) ? true : false);
}

// Allow us to change the input encoding from the URL string if we want to. (optional)
if (!empty($_GET['input']))
{
	$feed->set_input_encoding($_GET['input']);
}

// Allow us to choose to not re-order the items by date. (optional)
if (!empty($_GET['orderbydate']) && $_GET['orderbydate'] == 'false')
{
	$feed->enable_order_by_date(false);
}

// Allow us to cache images in feeds.  This will also bypass any hotlink blocking put in place by the website.
if (!empty($_GET['image']) && $_GET['image'] == 'true')
{
	$feed->set_image_handler('./handler_image.php');
}

// We'll enable the discovering and caching of favicons.
$feed->set_favicon_handler('./handler_image.php');

// Initialize the whole SimplePie object.  Read the feed, process it, parse it, cache it, and 
// all that other good stuff.  The feed's information will not be available to SimplePie before 
// this is called.
$success = $feed->init();

// We'll make sure that the right content type and character encoding gets set automatically.
// This function will grab the proper character encoding, as well as set the content type to text/html.
$feed->handle_content_type();

// When we end our PHP block, we want to make sure our DOCTYPE is on the top line to make 
// sure that the browser snaps into Standards Mode.
?><!DOCTYPE html>
<title>SimplePie: Demo</title>

<link rel="stylesheet" href="./for_the_demo/simplepie.css" type="text/css" media="screen, projector" />


<div id="site">

	<div id="content">

		<div class="chunk">
			<form action="" method="get" name="sp_form" id="sp_form">
				<div id="sp_input">


					<!-- If a feed has already been passed through the form, then make sure that the URL remains in the form field. -->
					<p><input type="text" name="feed" value="<?php if ($feed->subscribe_url()) echo $feed->subscribe_url(); ?>" class="text" id="feed_input" />&nbsp;<input type="submit" value="Read" class="button" /></p>


				</div>
			</form>


			<?php
			// Check to see if there are more than zero errors (i.e. if there are any errors at all)
			if ($feed->error())
			{
				// If so, start a <div> element with a classname so we can style it.
				echo '<div class="sp_errors">' . "\r\n";

					// ... and display it.
					echo '<p>' . htmlspecialchars($feed->error()) . "</p>\r\n";

				// Close the <div> element we opened.
				echo '</div>' . "\r\n";
			}
			?>

			<!-- Here are some sample feeds. -->
			<p class="sample_feeds"><strong>Or try one of the following:</strong>
<?php
while (list($feedset, $feeds) = each($feedsets)) {
	$feedset_uri = $feed_uri . '?feedset=' . $feedset;
	$title = $feedset;
	if (isset($feeds['title'])) $title = $feeds['title'];
	echo '<a href="'.$folder_uri.'output-html.php?feed=' . $feedset_uri . '">'.$title.'</a>, ';
}
?>
		</div>

		<div id="sp_results">

			<!-- As long as the feed has data to work with... -->
			<?php if ($success): ?>
				<div class="chunk focus" align="center">

					<!-- If the feed has a link back to the site that publishes it (which 99% of them do), link the feed's title to it. -->
					<h1><?php if ($feed->get_link()) echo '<a href="' . $feed->get_link() . '">'; echo $feed->get_title(); if ($feed->get_link()) echo '</a>'; ?></h1>

					<!-- If the feed has a description, display it. -->
					<?php echo $feed->get_description(); ?>

				</div>

				<!-- Add subscribe links for several different aggregation services -->
				<p class="subscribe"><strong>Subscribe:</strong> <a href="<?php echo $feed->subscribe_bloglines(); ?>">Bloglines</a>, <a href="<?php echo $feed->subscribe_google(); ?>">Google Reader</a>, <a href="<?php echo $feed->subscribe_msn(); ?>">My MSN</a>, <a href="<?php echo $feed->subscribe_netvibes(); ?>">Netvibes</a>, <a href="<?php echo $feed->subscribe_newsburst(); ?>">Newsburst</a><br /><a href="<?php echo $feed->subscribe_newsgator(); ?>">Newsgator</a>, <a href="<?php echo $feed->subscribe_odeo(); ?>">Odeo</a>, <a href="<?php echo $feed->subscribe_podnova(); ?>">Podnova</a>, <a href="<?php echo $feed->subscribe_rojo(); ?>">Rojo</a>, <a href="<?php echo $feed->subscribe_yahoo(); ?>">My Yahoo!</a>, <a href="<?php echo $feed->subscribe_feed(); ?>">Desktop Reader</a></p>


				<!-- Let's begin looping through each individual news item in the feed. -->
				<?php foreach($feed->get_items() as $item): ?>
					<div class="chunk">

						<?php
						// Let's add a favicon for each item. If one doesn't exist, we'll use an alternate one.
						if (!$favicon = $feed->get_favicon())
						{
							$favicon = './for_the_demo/favicons/alternate.png';
						}
						?>

						<!-- If the item has a permalink back to the original post (which 99% of them do), link the item's title to it. -->
						<h4><img src="<?php echo $favicon; ?>" alt="Favicon" class="favicon" /><?php if ($item->get_permalink()) echo '<a href="' . $item->get_permalink() . '">'; echo $item->get_title(); if ($item->get_permalink()) echo '</a>'; ?>&nbsp;<span class="footnote"><?php echo $item->get_date('j M Y, g:i a'); ?></span></h4>

						<!-- Display the item's primary content. -->
						<?php echo $item->get_content(); ?>

						<?php
						// Check for enclosures.  If an item has any, set the first one to the $enclosure variable.
						if ($enclosure = $item->get_enclosure(0))
						{
							// Use the embed() method to embed the enclosure into the page inline.
							echo '<div align="center">';
							echo '<p>' . $enclosure->embed(array(
								'audio' => './for_the_demo/place_audio.png',
								'video' => './for_the_demo/place_video.png',
								'mediaplayer' => './for_the_demo/mediaplayer.swf',
								'alt' => '<img src="./for_the_demo/mini_podcast.png" class="download" border="0" title="Download the Podcast (' . $enclosure->get_extension() . '; ' . $enclosure->get_size() . ' MB)" />',
								'altclass' => 'download'
							)) . '</p>';
							echo '<p class="footnote" align="center">(' . $enclosure->get_type();
							if ($enclosure->get_size())
							{
								echo '; ' . $enclosure->get_size() . ' MB';								
							}
							echo ')</p>';
							echo '</div>';
						}
						?>

						<!-- Add links to add this post to one of a handful of services. -->
						<p class="footnote favicons" align="center">
							<a href="<?php echo $item->add_to_blinklist(); ?>" title="Add post to Blinklist"><img src="./for_the_demo/favicons/blinklist.png" alt="Blinklist" /></a>
							<a href="<?php echo $item->add_to_blogmarks(); ?>" title="Add post to Blogmarks"><img src="./for_the_demo/favicons/blogmarks.png" alt="Blogmarks" /></a>
							<a href="<?php echo $item->add_to_delicious(); ?>" title="Add post to del.icio.us"><img src="./for_the_demo/favicons/delicious.png" alt="del.icio.us" /></a>
							<a href="<?php echo $item->add_to_digg(); ?>" title="Digg this!"><img src="./for_the_demo/favicons/digg.png" alt="Digg" /></a>
							<a href="<?php echo $item->add_to_magnolia(); ?>" title="Add post to Ma.gnolia"><img src="./for_the_demo/favicons/magnolia.png" alt="Ma.gnolia" /></a>
							<a href="<?php echo $item->add_to_myweb20(); ?>" title="Add post to My Web 2.0"><img src="./for_the_demo/favicons/myweb2.png" alt="My Web 2.0" /></a>
							<a href="<?php echo $item->add_to_newsvine(); ?>" title="Add post to Newsvine"><img src="./for_the_demo/favicons/newsvine.png" alt="Newsvine" /></a>
							<a href="<?php echo $item->add_to_reddit(); ?>" title="Add post to Reddit"><img src="./for_the_demo/favicons/reddit.png" alt="Reddit" /></a>
							<a href="<?php echo $item->add_to_segnalo(); ?>" title="Add post to Segnalo"><img src="./for_the_demo/favicons/segnalo.png" alt="Segnalo" /></a>
							<a href="<?php echo $item->add_to_simpy(); ?>" title="Add post to Simpy"><img src="./for_the_demo/favicons/simpy.png" alt="Simpy" /></a>
							<a href="<?php echo $item->add_to_spurl(); ?>" title="Add post to Spurl"><img src="./for_the_demo/favicons/spurl.png" alt="Spurl" /></a>
							<a href="<?php echo $item->add_to_wists(); ?>" title="Add post to Wists"><img src="./for_the_demo/favicons/wists.png" alt="Wists" /></a>
							<a href="<?php echo $item->search_technorati(); ?>" title="Who's linking to this post?"><img src="./for_the_demo/favicons/technorati.png" alt="Technorati" /></a>
						</p>

					</div>

				<!-- Stop looping through each item once we've gone through all of them. -->
				<?php endforeach; ?>

			<!-- From here on, we're no longer using data from the feed. -->
			<?php endif; ?>

		</div>

		<div>
			<!-- Display how fast the page was rendered. -->
			<p class="footnote">Page processed in <?php $mtime = explode(' ', microtime()); echo round($mtime[0] + $mtime[1] - $starttime, 3); ?> seconds.</p>

			<!-- Display the version of SimplePie being loaded. -->
			<p class="footnote">Powered by <a href="<?php echo SIMPLEPIE_URL; ?>"><?php echo SIMPLEPIE_NAME . ' ' . SIMPLEPIE_VERSION . ', Build ' . SIMPLEPIE_BUILD; ?></a>.  SimplePie is &copy; 2004&ndash;<?php echo date('Y'); ?>, Ryan Parman and Geoffrey Sneddon, and licensed under the <a href="http://www.opensource.org/licenses/bsd-license.php">BSD License</a>.</p>
		</div>

	</div>

</div>

</body>
</html>
