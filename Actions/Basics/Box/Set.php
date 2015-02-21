<?php

$Box = new \Franklin\Basics\Box($VirtualParent);

$Box->Id = $Data['Box_Id'];
$Box->Name = $Data['Box_Name'];
$Box->Title = $Data['Box_Title'];
$Box->Content = $Data['Box_Content'];
$Box->CleanURL = $Data['Box_CleanURL'];
/*$Box->Gallery->Id = $Data['Gallery_Id'];
$Box->Gallery->Load();*/

$Results[] = $Box->Set();
