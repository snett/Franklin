<?php

$Menu = new \Franklin\Basics\Menu($VirtualParent);

$Menu->Id = $Data['Menu_Id'];
$Menu->Name = $Data['Menu_Name'];
$Menu->CleanURL = $Data['Menu_CleanURL'];

$Results[] = $Menu->Create();
