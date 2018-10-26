<?php
	include "library/dbInterface.php";

	/* 
		Get articles for this date in history
	*/
	$url = "https://history.muffinlabs.com/date";
	$json = file_get_contents($url);
	$jsonData = json_decode($json, true);
	$raw = $jsonData["data"]["Events"];
	$articles = "";
	foreach($raw as &$thing){
		print_r($thing);
		echo "<br><br><br><br><br>";
	}

	/*
		- Get intro to wiki article associated with each article 
		returned by above query.
		- Print results of each article
	*//*
	$url = "https://en.wikipedia.org/w/api.php?" .
			"action=query&" .
			"prop=extracts&" .
			"format=json&" .
			"exintro=&" .
			"titles=" . $articles;
	$json = file_get_contents($url);
	$jsonData = json_decode($json, true);
	$raw = $jsonData["query"]["pages"];
	foreach($raw as &$value){
		$title = $value["title"];
		$text = $value["extract"];
		if($title != "" && $text != ""){
			// Get image associated with article
			$url = "https://www.googleapis.com/customsearch/v1?" .
				   "key=AIzaSyBK_Mpy8rq3ODjB4U_RXmZbiQddK2-o9nQ&" . 
				   "cx=005602527246330419495:yz89uand8la&" . 
				   "searchType=image&" .
				   "safe=active&" . 
				   "imgSize=large&" .
				   "q=" . str_replace(" ", "%20", $title);
			$jsonGoog = file_get_contents($url);
			$jsonGoogData = json_decode($jsonGoog, true);
			$imgUrl = $jsonGoogData["items"][0]["link"];
			$queryType = 0;

			// Use dbInsert() from dbInterface.php store article data
			$table = "wikiArticle";
			$cols = array("title","text", "imgUrl", "date", "queryType");
			$vals = array($title, $text, $imgUrl, $date, $queryType);
			$dbResult = dbInsert($table, $cols, $vals, $dbc);
		}
	}*/
?>