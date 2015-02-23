<?php

$Sidebar = new \Franklin\Component\Sidebar($VirtualParent);

$Sidebar->Id = $Data['Sidebar_Id'];
$Sidebar->Name = $Data['Sidebar_Name'];
$Sidebar->Title = $Data['Sidebar_Title'];
$Sidebar->Content = $Data['Sidebar_Content'];
$Sidebar->CleanURL = $Data['Sidebar_CleanURL'];
/*$Sidebar->Gallery->Id = $Data['Gallery_Id'];
$Sidebar->Gallery->Load();*/

$Results[] = $Sidebar->Set();
