<?php
    /*
        - Store comment made by user in "comment" table of agora DB
    */
	session_start();
	include "library/dbInterface.php";

    $header = 'Location: article.php';
	if(!empty($_POST["objectId"])) $objectId = strip_tags(trim($_POST['objectId']));
    else $objectId = 0;

    if(!empty($_POST["submit"]) && 
    !empty($_POST["newComment"]) && 
    !empty($_POST["parentId"]) &&
    !empty($_POST["objectId"])){
    	$comment = $_POST['newComment'];
    	$comment = htmlspecialchars($comment, ENT_QUOTES);
    	$comment = strip_tags(trim($comment));

    	$parentId = strip_tags(trim($_POST['parentId']));
    	if($parentId == "none") $parentId = 0;
    	$objectId = strip_tags(trim($_POST['objectId']));
    	$header .= '?article=' . $objectId;

    	if($comment != ""){
            $table = "comment";
            $cols = array("objectId",  "parentId", "text");
            $vals = array($objectId, $parentId, $comment);
            if(dbInsert($table, $cols, $vals, $dbc)){
                $header .= '&succss=1';
            } else {
                $header .= '&error=0';
            }
    	} else {
    		$header .= '&error=1';
    	}
    } else {
    	$header .= '?article=' . $objectId;
        $header .= '&error=2';
    }
	header($header);
	exit();  
?>