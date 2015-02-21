<?php

$Box = new \Franklin\Basics\Box($VirtualParent);

$Box->Id = $Data['Box_Id'];
$Box->Name = $Data['Box_Name'];
$Box->Title = $Data['Box_Title'];
$Box->Content = $Data['Box_Content'];
$Box->CleanURL = $Data['Box_CleanURL'];

$Results[] = $Box->Create();
