#!/usr/bin/php
<?php
	// TODO: Add description
	
	// Test URL: https://horriblesubs.info/shows/jojos-bizarre-adventure-stardust-crusaders/
	// Corrisponding API URL: https://horriblesubs.info/api.php?method=getshows&type=show&showid=218
	// Xpath: /html/body/div/div/div[2]/div[2]/div[1]/div/main/div[1]/article/div/script
	
	// Check if argument given
	if(!isset($argv[1])) {
		die("No link given.".PHP_EOL.
		    "Usage: ".$argv[0]." link [resolution] [output file]".PHP_EOL);
	}
	
	// Load in URL from argument and check validity of URL
	$URL = $argv[1];
	if(!preg_match('/https:\/\/horriblesubs.info\/shows\/[\w-]+\//', $URL))
		die("URL does not seem to be valid!".PHP_EOL);
	
	// Set resolution
	$resolution_id=0;
	if(isset($argv[2])) {
		switch($argv[2]) {
			case "480p":
				$resolution_id=1;
				echo "Resolution set to 480p".PHP_EOL;
				break;
			case "720p":
				$resolution_id=2;
				echo "Resolution set to 720p".PHP_EOL;
				break;
			case "1080p":
				$resolution_id=3;
				echo "Resolution set to 1080p".PHP_EOL;
				break;
			default:
				die("Unrecognised resolution");
		}
	} else {
		$resolution_id=3;
		echo "Resolution not set. Set to 1080p".PHP_EOL;
	}
	
	// Optionally set output file
	$output_file_res=false;
	if(isset($argv[3])) {
		$output_file_res=@fopen($argv[3], "w");
		if($output_file_res === false)
			die("Can't open file for writing".PHP_EOL);
	}
	
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
			$magnet_link=$node->xpath("div/div[$resolution_id]/span[2]/a")[0]["href"];
			// Either write to file or print to stdout
			if($output_file_res)
				fwrite($output_file_res, $magnet_link.PHP_EOL);
			else
				echo $magnet_link.PHP_EOL;
		}
	}
	
	fclose($output_file_res);
?>
