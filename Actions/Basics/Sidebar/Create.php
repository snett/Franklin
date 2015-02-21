<?php

$Sidebar = new \Franklin\Basics\Sidebar($VirtualParent);

$Sidebar->Id = $Data['Sidebar_Id'];
$Sidebar->Name = $Data['Sidebar_Name'];
$Sidebar->Title = $Data['Sidebar_Title'];
$Sidebar->Content = $Data['Sidebar_Content'];
$Sidebar->CleanURL = $Data['Sidebar_CleanURL'];

$Results[] = $Sidebar->Create();
