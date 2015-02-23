<?php

$CommentObject = new \Franklin\Forum\Comment($VirtualParent);
$GLOBALS['CommentObject'] = &$CommentObject;

$SiteCode = $Data['Site'];
$Site = new \Franklin\Basic\Site($VirtualParent);
$Site->LoadByCode($SiteCode);

$Comments = $CommentObject->Ls("`Group`=1 AND `Status`=1 AND `Parent`=0 AND `Site`='$Site->Id'", "`Id` DESC");
$CommentList = array();
foreach ($Comments as $Comment) {
    $CID = $Comment->Id;
    $CommentElement['Id'] = $CID;
    $CommentElement['UserName'] = $Comment->UserName;
    $CommentElement['UserEmail'] = $Comment->UserEmail;
    $CommentElement['Content'] = $Comment->Content;
    $CommentElement['Date'] = $Comment->TimeCreated;
    $CommentElement['hasChild'] = (bool) $Comment->hasChild;
    $CommentElement['SubComments'] = array();
    if ($Comment->hasChild === true){
        foreach ($Comment->Children as $SubComment) {
            $SubCommentElement['Id'] = $CID;
            $SubCommentElement['UserName'] = $SubComment->UserName;
            $SubCommentElement['UserEmail'] = $SubComment->UserEmail;
            $SubCommentElement['Content'] = $SubComment->Content;
            $SubCommentElement['Date'] = $SubComment->TimeCreated;
            $SubCommentElement['hasChild'] = $SubComment->hasChild;
            $CommentElement['SubComments'][] = $SubCommentElement;
        }
    }
    $CommentList[] = $CommentElement;
}

$Results['Forum.Comment.Ls'] = $CommentList;