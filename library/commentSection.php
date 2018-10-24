<?php
    error_reporting(0);
    if(!function_exists("dbInsert")) include "dbInterface.php";

    /*
        - Perform select to find comments associated with article
        - Leverage the dom in javascript to present comments in reddit like format
    */
    $articles = array();
    $commentSectionAll = "";
    if(isset($article)){
	    $table = "comment";
	    $cols = array("id", "parentId", "text", "time_stamp");
	    $where1 = array("objectId");
	    $where2 = array($article);
	    $limit = "1000";
	    $orderBy = "id";
	    $return = dbSelect($table, $cols, $where1, $where2, $limit, $orderBy, $dbc);
	    $dbc->close();

        /*
            - Build html for comment
            - Get parent comment's element id
            - Add javascript dom manipulation to insert html into parent's div
        */
	    foreach($return as &$element){
			$str = 
                '<div id = "comment' . $element[0] . '" class = "comment"><div class = "innerCommentText">' .
                	preg_replace("/(\r\n|\n)/", "<br>", $element[2]) . 
                	'<br>' .
                	'<a class = "noSelect" id = "replyButton' . $element[0] . 
                		'" style = "cursor: pointer;" onclick = "replyToComment(' . $element[0] . ')"><b>Reply</b>' . 
                	'</a>' . 
                	'<a class = "noSelect" id = "hideReplyButton' . $element[0] . 
                		'" style = "display:none; cursor: pointer;" onclick = "hideReplyToComment(' . $element[0] . ')"><b>Hide</b>' . 
                	'</a>' . 
                	'<div id = "commentContainer' . $element[0] . '"></div>' . 
                    '</div>' . 
                '</div>';
            
            if($element[1] == 0) $commentSectionAll .=  
            	'document.getElementById("commentSection").innerHTML += \'' . $str . '\';';
            else $commentSectionAll .=  
            	'if(document.getElementById("comment' . $element[1] . '"))' . 
            	'document.getElementById("comment' . $element[1] . '").innerHTML += \'' . $str . '\';';
		}
		$commentSectionAll = '<script> function addComments(){' . $commentSectionAll . '} </script>';
	}
?>

<script>
    /*
        - Dynamically build reply form
        - Remove form for comment previously being replied to
        - Build form based on which comment is now being replied to
    */
	var currentComment;
	function replyToComment(c){
		if(document.getElementById("commentContainer" + currentComment)){
			var oldCommentContainer = document.getElementById("commentContainer" + currentComment);
			while (oldCommentContainer.firstChild) {
			    oldCommentContainer.removeChild(oldCommentContainer.firstChild);
			}
		}

		currentComment = c;
		var newCommentContainer = document.getElementById("commentContainer" + c);
		newCommentContainer.innerHTML = 
			'<div id="replyToComment' + c + '">' +
            	'<form id = "makeComment' + c + '" action="submitComment.php" method="POST" enctype="multipart/form-data">' +
                    '<textarea class = "textBoxEmbed" name = "newComment" form = "makeComment' + c + 
                    '" placeholder = "Comment here" maxlength = "1024" required></textarea>' +
                    '<input type="hidden" name="parentId" value="' + c + '">' +
                    '<input type="hidden" name="objectId" value="<?php echo $article; ?>">' +
                    '<input name = "submit" type = "submit">' +
                '</form>' +
            '</div>';
	}
</script>

<?php
    // General comment reply to main article
    echo 
    	'<div class = "commentSection" id = "commentSection">' .
    		'<div id="replyToComment0">' .
            	'<form id = "makeComment0" action="submitComment.php" method="POST" enctype="multipart/form-data">' .
                    '<textarea class = "textBoxEmbed" name = "newComment" form = "makeComment0" placeholder = "Comment here" maxlength = "1024" required></textarea>' .
                    '<input type="hidden" name="parentId" value="none">' .
                    '<input type="hidden" name="objectId" value="' . $article . '">' .
                    '<input name = "submit" type = "submit">' .
                '</form>' .
            '</div><br>' .
    	'</div>'
    ;
	echo $commentSectionAll;
?>
<script> addComments(); </script>