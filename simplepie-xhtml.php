<?php
// SimplePie-based feed mashup
// http://frans.lowter.us/2010/02/14/simplepie-based-feed-mashup/
// Spread, use, modify, etc. as much as you like. A link back or a comment would be appreciated, but there's no need to. -Frans

// Include the SimplePie library, and the one that handles internationalized domain names.
require_once('./simplepie.inc');
require_once('./idn/idna_convert.class.php');

class SimplePie_XHTML extends SimplePie {
	function filter_entry($item, $filters) {
		$display = false;
		foreach ($filters as $filter) {
			$action = $filter[0];
			$target = $filter[1];
			$string = $filter[2];
			
			if ($action == 'permit') {
				if (preg_match("/$string/", $item->get_title()))
					$display = true;
			}
			elseif ($action == 'block') {
				if (!preg_match("/$string/", $item->get_title()))
					$display = true;
			}
		}
		return $display;
	}
	function fix_xhtml ($html) {
		// Robust, should catch everything.
		if ( function_exists('tidy_parse_string') ) {
			// Tidy config options at http://tidy.sourceforge.net/docs/quickref.html
			// Code almost verbatim from http://www.php.net/manual/en/tidy.examples.basic.php#89334
			$tidy_config = array(
				'clean' => true,
				'output-xhtml' => true,
				'show-body-only' => true,
				'drop-proprietary-attributes' => true, // "This option specifies if Tidy should strip out proprietary attributes, such as MS data binding attributes." Basically strips out any namespaced attributes which might break parsing otherwise.
				'wrap' => 0,
			);
			$tidy = tidy_parse_string($html, $tidy_config, 'UTF8');
			$tidy->cleanRepair();
			// See http://techtrouts.com/webkit-entity-nbsp-not-defined-convert-html-entities-to-xml/ and http://www.sourcerally.net/Scripts/39-Convert-HTML-Entities-to-XML-Entitie
			$xml_ent = array('&#34;','&#38;','&#38;','&#60;','&#62;','&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;'); 
			$html_ent = array('&quot;','&amp;','&amp;','&lt;','&gt;','&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;');
			$tidy = str_ireplace($html_ent,$xml_ent,$tidy); // Case insensitive for if people do stupid things like &NBSP;.
			return $tidy;
		}
		// Fixes some minor issues with SimplePie, but leaves fundamental problems with input.
		else {
			$xhtml;
			//$self_closing = array ('br', 'hr', 'input', 'img');
			$self_closing = array ('br'); ///Only those butchered by SimplePie go in here.
			for ($i = 0; $i < count($self_closing); $i++) {
				$xhtml = preg_replace ('/<'.$self_closing[$i].'(.*)>/sU','<'.$self_closing[$i].'\\1/>',$html);
			}
			return $xhtml;
		}
	}
}
?>