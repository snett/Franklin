<?php

$Menu = new \Franklin\Basics\Menu($VirtualParent);

$Menu->Id = $Data['Menu_Id'];

$Results[] = $Menu->Remove();
