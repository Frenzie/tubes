<!DOCTYPE html>
<title>Compatibility Tests</title>
<style>
dt {float: left;clear: left;}
</style>
<h1>Compatibility Tests</h1>
<h2>Results</h2>
<?php
$checks = array(
	// Some stuff from SimplePie's compatibility check.
	'php' => array('PHP 5.2.0+', (function_exists('version_compare') && version_compare(phpversion(), '5.2.0', '>='))),
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

echo '<dl>';
foreach($checks as $check) {
	echo '<dt>'.$check[0].':</dt>';
	echo '<dd>';
	if ( $check[1] ) {
		echo 'OK';
	}
	else {
		echo 'Darn';
	}
	echo '</dd>';
}
echo '</dl>';
?>
<h2>What does this mean?</h2>
<ol>
<?php
// Based on sp_compatibility_test.php
if ($checks['php'][1] && $checks['xml'][1] && $checks['pcre'][1] && $checks['mbstring'][1] && $checks['iconv'][1] && $checks['curl'][1] && $checks['zlib'][1] && $checks['tidy'][1] && $checks['cache'][1]) {
	echo '<li><em>You have everything you need to run SimplePie and Tubes properly!  Congratulations!</em></li>';
}
else {
	if ($checks['php'][1]) {
		echo '<li><strong>PHP:</strong> You are running a supported version of PHP.  <em>No problems here.</em></li>';
		if ($checks['xml'][1]) {
			echo '<li><strong>XML:</strong> You have XML support installed.  <em>No problems here.</em></li>';
			if ($checks['pcre'][1]) {
				echo '<li><strong>PCRE:</strong> You have PCRE support installed. <em>No problems here.</em></li>';
				if ($checks['curl'][1]) {
					echo '<li><strong>cURL:</strong> You have <code>cURL</code> support installed.  <em>No problems here.</em></li>';
				}
				else {
					echo '<li><strong>cURL:</strong> The <code>cURL</code> extension is not available.  SimplePie will use <code>fsockopen()</code> instead.</li>';
				}
				if ($checks['zlib'][1]) {
					echo '<li><strong>Zlib:</strong> You have <code>Zlib</code> enabled.  This allows SimplePie to support GZIP-encoded feeds.  <em>No problems here.</em></li>';
				}
				else {
					echo '<li><strong>Zlib:</strong> The <code>Zlib</code> extension is not available.  SimplePie will ignore any GZIP-encoding, and instead handle feeds as uncompressed text.</li>';
				}
				if ($checks['mbstring'][1] && $checks['iconv'][1]) {
					echo '<li><strong>mbstring and iconv:</strong> You have both <code>mbstring</code> and <code>iconv</code> installed!  This will allow SimplePie to handle the greatest number of languages.  Check the <a href="http://simplepie.org/wiki/faq/supported_character_encodings">Supported Character Encodings</a> chart to see what is supported on your webhost.</li>';
				}
				elseif ($checks['mbstring'][1]) {
					echo '<li><strong>mbstring:</strong> <code>mbstring</code> is installed, but <code>iconv</code> is not.  Check the <a href="http://simplepie.org/wiki/faq/supported_character_encodings">Supported Character Encodings</a> chart to see what is supported on your webhost.</li>';
				}
				elseif ($checks['iconv'][1]) {
					echo '<li><strong>iconv:</strong> <code>iconv</code> is installed, but <code>mbstring</code> is not.  Check the <a href="http://simplepie.org/wiki/faq/supported_character_encodings">Supported Character Encodings</a> chart to see what is supported on your webhost.</li>';
				}
				else {
					echo '<li><strong>mbstring and iconv:</strong> <em>You do not have either of the extensions installed.</em> This will significantly impair your ability to read non-English feeds, as well as even some English ones.  Check the <a href="http://simplepie.org/wiki/faq/supported_character_encodings">Supported Character Encodings</a> chart to see what is supported on your webhost.</li>';
				}
				if ($checks['tidy'][1]) {
					echo '<li><strong>Tidy:</strong> <code>Tidy</code> is installed. <em>No problems here.</em></li>';
				}
				else {
					echo '<li><strong>Tidy:</strong> <code>Tidy</code> is not installed. Tidy is recommended to ensure well-formedness.</li>';
				}
				if ($checks['cache'][1]) {
					echo '<li><strong>Cache:</strong> Cache directory is writable. <em>No problems here.</em></li>';
				}
				else {
					echo '<li><strong>Cache:</strong> Ensure that PHP has write access to the cache directory.</li>';
				}
			}
			else {
				echo '<li><strong>PCRE:</strong> Your PHP installation does not support Perl-Compatible Regular Expressions.  <em>SimplePie is a no-go at the moment.</em></li>';
			}
		}
		else {
			echo '<li><strong>XML:</strong> Your PHP installation does not support XML parsing.  <em>SimplePie is a no-go at the moment.</em></li>';
		}
	}
	else {
		echo '<li><strong>PHP:</strong> You are running an unsupported version of PHP.  <em>SimplePie is a no-go at the moment.</em></li>';
	}
}
?>
</ol>
