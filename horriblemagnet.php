<?php
	// TODO: Add description
	
	// Test URL: https://horriblesubs.info/shows/jojos-bizarre-adventure-stardust-crusaders/
	// Corrisponding API URL: https://horriblesubs.info/api.php?method=getshows&type=show&showid=218
	// Xpath: /html/body/div/div/div[2]/div[2]/div[1]/div/main/div[1]/article/div/script
	
	// Load in URL from argument
	$URL = $argv[1];
	
	// TODO: add way to select resolution (now extracts 1080p only)
	
	// Check validity of URL
	if(!preg_match('/https:\/\/horriblesubs.info\/shows\/[\w-]+\//', $URL))
		die("URL does not seem to be valid!".PHP_EOL);
	
	// Load DOM
	$dom = new DOMDocument();
	@$dom->loadHTMLFile($URL);
	
	// Extract show id
	$js_number = trim(simplexml_import_dom($dom)->xpath("/html/body/div/div/div[2]/div[2]/div[1]/div/main/div[1]/article/div/script")[0]->__toString()); // Example: var hs_showid = 218;
	$showid_arr=array();
	preg_match('/.+ (\d+);/', $js_number, $showid_arr);
	$showid = $showid_arr[1];
	
	// Start extracting magnet links via pagination
	$api_url = "https://horriblesubs.info/api.php?method=getshows&type=show&showid=$showid&nextid=0";
	for($i=0; ("DONE" != file_get_contents($api_url)); $i++) { // If API returns "DONE" we have no more pages
		$api_url = "https://horriblesubs.info/api.php?method=getshows&type=show&showid=$showid&nextid=$i";
		
		$dom_api = new DOMDocument();
		@$dom_api->loadHTMLFile($api_url);
		
		$xml = simplexml_import_dom($dom_api)->xpath("body/div");
		foreach($xml as $node) {
			// Just print the magnet link. Maybe we will add a more sophisticated way later
			echo $node->xpath("div/div[3]/span[2]/a")[0]["href"].PHP_EOL;
		}
	}
?>
