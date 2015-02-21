<?php

$Page = new \Franklin\Basics\Page($VirtualParent);

$Page->Id = $Data['Page_Id'];
$Page->Name = $Data['Page_Name'];
$Page->Intro = $Data['Page_Intro'];
$Page->Content = $Data['Page_Content'];
$Page->CleanURL = $Data['Page_CleanURL'];
$Page->Language->Id = $Data['Page_Language_Id'];

$Results[] = $Page->Create();
