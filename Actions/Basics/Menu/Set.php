<?php

$Menu = new \Franklin\Component\Menu($VirtualParent);

$Menu->Id = $Data['Menu_Id'];
$Menu->Name = $Data['Menu_Name'];
$Menu->CleanURL = $Data['Menu_CleanURL'];

$Results[] = $Menu->Set();
