<?php
	/*
		Produce the icons/links to various websites and their results for a given article
	*/
	function generateMediaLinks($title){
		$title = str_replace(" ", "%20", $title);
		$tmp = array();
		array_push($tmp, 
			'<a href="https://en.wikipedia.org/wiki/' . 
				$title . '" class = "noSelect">' . mediaLink("wiki") . 
			'</a>'
		);
		array_push($tmp, 
			'<a href="https://twitter.com/search?q=' . 
				$title . '" class = "noSelect">' . mediaLink("twitter") . 
			'</a>'
		);
		array_push($tmp, 
			'<a href="https://www.pinterest.com/search/pins/?q=' . 
				$title . '" class = "noSelect">' . mediaLink("pinterest") . 
			'</a>'
		);
		array_push($tmp, 
			'<a href="https://www.youtube.com/results?search_query=' . 
				$title . '" class = "noSelect">' . mediaLink("youtube") . 
			'</a>'
		);
		array_push($tmp, 
			'<a href="https://www.reddit.com/search?q=' . 
				$title . '" class = "noSelect"> ' . mediaLink("reddit") . 
			'</a>'
		);
		array_push($tmp, 
			'<a href="https://search.yahoo.com/search?p=' . 
				$title . '" class = "noSelect">' . mediaLink("yahoo") . 
			'</a>'
		);
		array_push($tmp, 
			'<a href="https://www.google.com/search?q=' . 
				$title . '" class = "noSelect">' . mediaLink("google") . 
			'</a>'
		);
		array_push($tmp, 
			'<a href="https://www.flickr.com/search/?text=' . 
				$title . '" class = "noSelect">' . mediaLink("flickr") . 
			'</a>'
		);
		return $tmp;
	}

	/*
		Create and return the fontawesome icon for a given website
	*/
	function mediaLink($media){
		$iconFront = '<div class = "socialMediaIconContainer reactiveHover pointer"><i class="fa ';
		$iconBack = ' fa-1x"></i></div>';
		if($media == "wiki") $iconMid = 'fa-wikipedia-w';
		elseif($media == "twitter") $iconMid = 'fa-twitter';
		elseif($media == "pinterest") $iconMid = 'fa-pinterest-p';
		elseif($media == "youtube") $iconMid = 'fa-youtube';
		elseif($media == "reddit") $iconMid = 'fa-reddit-alien';
		elseif($media == "yahoo") $iconMid = 'fa-yahoo';
		elseif($media == "google") $iconMid = 'fa-google';
		elseif($media == "flickr") $iconMid = 'fa-flickr';
		else return "";
		return $iconFront . $iconMid . $iconBack;
	}
?>