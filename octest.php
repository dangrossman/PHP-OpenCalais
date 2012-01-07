<?php

/**
* Example usage for the Open Calais Tags class written by Dan Grossman
* (http://www.dangrossman.info). Read about this class and how to get
* an API key at http://www.dangrossman.info/open-calais-tags
*/

require('opencalais.php');

$apikey = "YOUR API KEY HERE";

$oc = new OpenCalais($apikey);

$content = <<<EOD

April 7 (Bloomberg) -- Yahoo! Inc., the Internet company that snubbed a $44.6 billion takeover bid from Microsoft Corp., may drop in Nasdaq trading after the software maker threatened to cut its bid if directors fail to give in soon.

If Yahoo's directors refuse to negotiate a deal within three weeks, Microsoft plans to nominate a board slate and take its case to investors, Chief Executive Officer Steve Ballmer said April 5 in a statement. He suggested the deal's value might decline if Microsoft has to take those steps.

The ultimatum may send Yahoo Chief Executive Officer Jerry Yang scrambling to find an appealing alternative for investors to avoid succumbing to Microsoft, whose bid was a 62 percent premium to Yahoo's stock price at the time. The deadline shows Microsoft is in a hurry to take on Google Inc., which dominates in Internet search, said analysts including Canaccord Adams's Colin Gillis. 

EOD;

$entities = $oc->getEntities($content);

foreach ($entities as $type => $values) {

	echo "<b>" . $type . "</b>";
	echo "<ul>";
	
	foreach ($values as $entity) {
		echo "<li>" . $entity . "</li>";
	}
	
	echo "</ul>";

}
