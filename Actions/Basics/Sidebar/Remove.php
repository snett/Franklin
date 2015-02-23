<?php

$Sidebar = new \Franklin\Component\Sidebar($VirtualParent);

$Sidebar->Id = $Data['Sidebar_Id'];

$Results[] = $Sidebar->Remove();
