<?php
	error_reporting(0);
	/*
		- Use api article results stored in agora -> wikiArticles to generate blocks of data for articles
		- display "this day in history"(soon^TM) and "current events" articles 
	*/

	/*
		Perform select to find articles for today
	*/
	include "library/dbInterface.php";
	$date = strtotime(date("Y-m-d") . " - 1 day");
	$date = date('Y/m/d', $date);
	$table = "wikiArticle";
	$cols = array("id", "imgUrl", "title", "text");
	$where1 = array("date");
	$where2 = array($date);
	$limit = "10";
	$orderBy = "id";
	$articles = dbSelect($table, $cols, $where1, $where2, $limit, $orderBy, $dbc);

	/*
		Generate block of data for each article returned by above query
	*/	
	$blocks = array();
	foreach($articles as &$article){
		array_push($blocks, 
			'<a href="article.php?article=' . $article[0] . '" class = "noSelect">' .
				'<div class = "articleContainer">' .
					'<div class = "articleImageContainer"><img class = "articleImage" src = "' . $article[1] . '"></div>' . 
					'<div class = "articleTextContainer">' . 
						'<h2 style = "margin-block-start: 0em;"><b>' . $article[2] . '</b></h2>' . 
						'<p>' .
							substr($article[3], 0, 256) . ' ...' . 
						'</p>' .
					'</div>' .
				'</div>' .
			'</a>'
		);
	}
?><!DOCTYPE HTML>

<html>
	<head>
		<title>Articles</title>
		<link rel="stylesheet" href="library/css/main.css" type="text/css"/>
		<link rel="stylesheet" href="library/css/articlesMobile.css" type="text/css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 	</head>

	<body>
		<div class = "centerContainer">
			<?php
				foreach($blocks as $block){
					echo $block;
				}
			?>
		</div>
	</body>
</html>
