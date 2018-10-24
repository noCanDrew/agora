<?php
	error_reporting(0);

	// Get api keys
	include "../../outerScripts/keys.php";

	if(isset($_GET["article"])){
		$article = trim(strip_tags($_GET["article"]));

		/*
	        - Perform select to get data for article
	        - Store formated result in $articleBody
	    */
	    include "library/dbInterface.php";
	    $table = "wikiArticle";
	    $cols = array("id", "imgUrl", "title", "text");
	    $where1 = array("id");
	    $where2 = array($article);
	    $limit = "1";
	    $orderBy = "";
	    $articles = dbSelect($table, $cols, $where1, $where2, $limit, $orderBy, $dbc);

	    /*
	    	- Wiki's fomrating post strip_tags is very weird
	    	- Still working on how to not delete the first char of paragraphs started by 
	    	the ".*" preg_replace below :/ 
	    */
	    $text = str_replace(" \n", " ", trim($articles[0][3]));
	    $text = str_replace(" \r\n", " ", $text);
	    $text = preg_replace("/\.[A-Za-z0-9]/", ".<br><br>", $text);
	    $text = preg_replace("/(\r\n|\n)/", "<br><br>", $text);

	    $articleBody = 
	    	'<img class = "articleBodyImage" src = "' . $articles[0][1] . '">' . 
	    	'<h1 style = "text-transform: capitalize;">' . $articles[0][2] . '</h1>' .
	    	'<p>' . $text . '</p>'
	    ;
		
		/*
			- Get social media icons related to article
			- Store formated result in $socialMediaBar
		*/
		include "library/getMediaIcons.php";
		$socialMediaElements = generateMediaLinks($articles[0][2]);
		$socialMediaBar = '<div class = "socialMediaContainer">';
		foreach($socialMediaElements as $element){
			$socialMediaBar .= $element;
		}
		$socialMediaBar .= '</div>';
	
		/*
			- Get current news articles specific to associated article
			- Generate list of news articles
		*/
		$date = strtotime(date("Y-m-d") . " - 1 week");
		$date = date('Y-m-d', $date);
		$url = "https://newsapi.org/v2/everything?" .
	          "q=" . str_replace(" ", "+", $articles[0][2]) . "&" .
	          "from=" . $date . "&" .
	          "sortBy=popularity&" .
	          "apiKey=" . $newsApiKey;
	    $json = file_get_contents($url);
		$json_data = json_decode($json, true);
		$raw = $json_data["articles"];
		$num = 0;
		$maxNumNews = 3;
		$news = array();
	    foreach($raw as $key => $sample){
	    	if(++$num > $maxNumNews) break;
			array_push($news,
				'<a class = "noSelect" href = "' . $sample["url"] . '">' . 
					'<div class = "adContainer">' .
						'<img class = "adImage" src = "' . $sample["urlToImage"] . '">' .
						'<div class = "adTextContainer">' .
							'<p><b>' . $sample["title"] . '</b></p>' . 
						'</div>' . 
					'</div>' . 
				'</a><br>'
			);
		}
	}
?><!DOCTYPE HTML>

<html>
	<head>
		<title>Article</title>
		<link rel="stylesheet" href="library/css/main.css" type="text/css"/>
		<link rel="stylesheet" href="library/css/comments.css" type="text/css">
		<link rel="stylesheet" href="library/css/articlesMobile.css" type="text/css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 	</head>

	<body>
		<div class = "centerContainer" style = "box-sizing: border-box; padding: 64px;">
			<?php
				echo $articleBody;
				echo $socialMediaBar; 
				foreach($news as $story){
					echo $story;
				}
				include "library/commentSection.php";
			?>
		</div>
	</body>
</html>