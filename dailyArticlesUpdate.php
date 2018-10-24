<?php
	error_reporting(0);
	include "library/dbInterface.php";
	include "../../outerScripts/keys.php";

	if($_GET["hash"] = "rds25g4rt6ws4edzhnurdsf48gvrtfj8ass"){
		/* 
			Get most popular wiki articles of the given day
		*/
		$date = strtotime(date("Y-m-d") . " - 1 day");
		$date = date('Y/m/d', $date);
		$url = "https://wikimedia.org/api/rest_v1/metrics/pageviews/top/en.wikipedia/all-access/" . $date;
		$json = file_get_contents($url);
		$jsonData = json_decode($json, true);
		$raw = $jsonData["items"]["0"]["articles"];
		$articles = "";

		/*
			- Skip first return because it is wiki homepage
			- Skip "xHamster" specifically because the whole stego thing :/
		*/
		for($a = 1; $a <= 10; $a++){
			$article = $raw[$a]["article"];
			if($article != "Special:Search" && $article != "xHamster"){
				$articles .= $article . "|";
			}
		}

		/*
			- Get intro to wiki article associated with each article returned by above query.
			- Print results of each article
		*/
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
					   "key=" . $googApiKey . "&" . 
					   "cx=" . $googApiEngine . "&" . 
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
		}
	}
?>