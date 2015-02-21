<?php

$Sidebar = new \Franklin\Basics\Sidebar($VirtualParent);

$Sidebar->Id = $Data['Sidebar_Id'];

$Results[] = $Sidebar->Remove();
