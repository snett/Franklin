<?php

$Page = new \Franklin\Component\Page($VirtualParent);

$Page->Id = $Data['Page_Id'];
$Page->Name = $Data['Page_Name'];
$Page->Intro = $Data['Page_Intro'];
$Page->Content = $Data['Page_Content'];
$Page->CleanURL = $Data['Page_CleanURL'];
$Page->Language->Id = $Data['Page_Language_Id'];
/*$Page->Gallery->Id = $Data['Gallery_Id'];
$Page->Gallery->Load();*/

$Results[] = $Page->Set();
