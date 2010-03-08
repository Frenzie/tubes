<!DOCTYPE html>
<title>Compatibility Tests</title>
<h1>Compatibility Tests</h1>
<h2>Results</h2>
<?php
$checks = array(
	// Some stuff from SimplePie's compatibility check.
	'php' => array('PHP 4.3.0+', (function_exists('version_compare') && version_compare(phpversion(), '4.3.0', '>='))),
	'xml' => array('XML', extension_loaded('xml')),
	'pcre' => array('PCRE', extension_loaded('pcre')),
	'curl' => array('cURL', function_exists('curl_exec')),
	'zlib' => array('Zlib', extension_loaded('zlib')),
	'mbstring' => array('mbstring', extension_loaded('mbstring')),
	'iconv' => array('iconv', extension_loaded('iconv')),
	// New stuff.
	'tidy' => array('Tidy', function_exists('tidy_parse_string')),
	'cache' => array('Cache', is_writable('./cache/')),
);

foreach($checks as $check) {
	echo $check[0].': ';
	if ( $check[1] ) {
		echo 'OK';
	}
	else {
		echo 'Darn';
	}
	echo '<br>';
}
?>
<!-- blatantly taken from sp_compatibility_test.php - either replace or update for the extra two checks -->
<h2>What does this mean?</h2>
<ol>
	<?php if ($checks['php'][1] && $checks['xml'][1] && $checks['pcre'][1] && $checks['mbstring'][1] && $checks['iconv'][1] && $checks['curl'][1] && $checks['zlib'][1]): ?>
	<li><em>You have everything you need to run SimplePie properly!  Congratulations!</em></li>
	<?php else: ?>
		<?php if ($checks['php'][1]): ?>
<li><strong>PHP:</strong> You are running a supported version of PHP.  <em>No problems here.</em></li>
<?php if ($checks['xml'][1]): ?>
	<li><strong>XML:</strong> You have XML support installed.  <em>No problems here.</em></li>
	<?php if ($checks['pcre'][1]): ?>
		<li><strong>PCRE:</strong> You have PCRE support installed. <em>No problems here.</em></li>
		<?php if ($checks['curl'][1]): ?>
<li><strong>cURL:</strong> You have <code>cURL</code> support installed.  <em>No problems here.</em></li>
		<?php else: ?>
<li><strong>cURL:</strong> The <code>cURL</code> extension is not available.  SimplePie will use <code>fsockopen()</code> instead.</li>
		<?php endif; ?>
	
		<?php if ($checks['zlib'][1]): ?>
<li><strong>Zlib:</strong> You have <code>Zlib</code> enabled.  This allows SimplePie to support GZIP-encoded feeds.  <em>No problems here.</em></li>
		<?php else: ?>
<li><strong>Zlib:</strong> The <code>Zlib</code> extension is not available.  SimplePie will ignore any GZIP-encoding, and instead handle feeds as uncompressed text.</li>
		<?php endif; ?>
	
		<?php if ($checks['mbstring'][1] && $checks['iconv'][1]): ?>
<li><strong>mbstring and iconv:</strong> You have both <code>mbstring</code> and <code>iconv</code> installed!  This will allow SimplePie to handle the greatest number of languages.  Check the <a href="http://simplepie.org/wiki/faq/supported_character_encodings">Supported Character Encodings</a> chart to see what's supported on your webhost.</li>
		<?php elseif ($checks['mbstring'][1]): ?>
<li><strong>mbstring:</strong> <code>mbstring</code> is installed, but <code>iconv</code> is not.  Check the <a href="http://simplepie.org/wiki/faq/supported_character_encodings">Supported Character Encodings</a> chart to see what's supported on your webhost.</li>
		<?php elseif ($checks['iconv'][1]): ?>
<li><strong>iconv:</strong> <code>iconv</code> is installed, but <code>mbstring</code> is not.  Check the <a href="http://simplepie.org/wiki/faq/supported_character_encodings">Supported Character Encodings</a> chart to see what's supported on your webhost.</li>
		<?php else: ?>
<li><strong>mbstring and iconv:</strong> <em>You do not have either of the extensions installed.</em> This will significantly impair your ability to read non-English feeds, as well as even some English ones.  Check the <a href="http://simplepie.org/wiki/faq/supported_character_encodings">Supported Character Encodings</a> chart to see what's supported on your webhost.</li>
		<?php endif; ?>
	<?php else: ?>
		<li><strong>PCRE:</strong> Your PHP installation doesn't support Perl-Compatible Regular Expressions.  <em>SimplePie is a no-go at the moment.</em></li>
	<?php endif; ?>
<?php else: ?>
	<li><strong>XML:</strong> Your PHP installation doesn't support XML parsing.  <em>SimplePie is a no-go at the moment.</em></li>
<?php endif; ?>
		<?php else: ?>
<li><strong>PHP:</strong> You are running an unsupported version of PHP.  <em>SimplePie is a no-go at the moment.</em></li>
		<?php endif; ?>
	<?php endif; ?>
</ol>